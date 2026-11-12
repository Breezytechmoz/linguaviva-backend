<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Models\Report;
use App\Models\PaymentRequest;
use App\Models\TranslatorApplication;
use App\Models\Translation;
use App\Models\TranslatorWallet;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // 1. Mostra o formulário de login
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // 2. Processa o login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            if (Auth::user()->is_admin == 1) {
                return redirect()->intended('/admin');
            }
            
            Auth::logout();
            return back()->withErrors(['email' => 'Não tens permissões de administrador.']);
        }

        return back()->withErrors(['email' => 'E-mail ou palavra-passe incorretos.']);
    }

    // 3. O Painel Principal
    public function index()
    {
        $totalUsers = User::count();
        $totalMessages = Message::count();
        $users = User::orderBy('created_at', 'desc')->get();
        $pagamentosPendentes = PaymentRequest::where('status', 'pending')->with('user')->get();
        
        // Correção: A tabela de reports não tem 'status', por isso trazemos todas as denúncias
        $denuncias = Report::with(['reporter', 'reported'])->orderBy('created_at', 'desc')->get();
        
        $candidaturas = TranslatorApplication::where('status', 'pending')->with('user')->get();
        $traducoesPendentes = Translation::where('status', 'pending')->with(['translator', 'language'])->get();

        return view('admin.dashboard', compact('totalUsers', 'totalMessages', 'users', 'pagamentosPendentes', 'denuncias', 'candidaturas', 'traducoesPendentes'));
    }

    // === NOVAS PÁGINAS DA SIDEBAR ===
    
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    public function payments()
    {
        $pagamentos = PaymentRequest::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.payments', compact('pagamentos'));
    }

    public function moderation()
    {
        $denuncias = Report::with(['reporter', 'reported'])->orderBy('created_at', 'desc')->get();
        return view('admin.moderation', compact('denuncias'));
    }

    // === AÇÕES ===

    public function handleTranslation($id, $action)
    {
        $translation = Translation::findOrFail($id);
        
        if ($translation->status !== 'pending') return back();

        if ($action === 'approve') {
            $translation->status = 'approved';
            $translation->save();

            $wallet = TranslatorWallet::firstOrCreate(['user_id' => $translation->translator_id]);
            $wallet->balance += 0.50;
            $wallet->total_earned += 0.50;
            $wallet->save();
        } else {
            $translation->status = 'rejected';
            $translation->save();
        }
        return back();
    }
    
    public function handlePayment(Request $request, $id)
    {
        $payment = PaymentRequest::findOrFail($id);
        $action = $request->input('action');

        if ($action === 'approve') {
            $payment->status = 'approved';
            $payment->save();

            $user = $payment->user;
            $user->is_premium = true;
            $user->premium_expires_at = now()->addDays(30);
            $user->save();
        } else {
            $payment->status = 'rejected';
            $payment->save();
        }

        return back();
    }
    
    public function toggleSuspend($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->is_admin) {
            return back()->withErrors(['msg' => 'Não podes suspender um administrador.']);
        }

        $user->is_suspended = !$user->is_suspended;
        $user->save();

        return back();
    }

    public function approveTranslator($id, $action)
    {
        $app = TranslatorApplication::findOrFail($id);
        
        if ($action === 'approve') {
            $app->status = 'approved';
            $app->save();
            
            $user = $app->user;
            $user->is_translator = true;
            
            $langs = json_decode($app->language_requested, true);
            if (is_array($langs)) {
                $user->translator_languages = $langs;
            } else {
                $user->translator_languages = [$app->language_requested]; 
            }
            
            $user->save();
            TranslatorWallet::firstOrCreate(['user_id' => $user->id]);
        } else {
            $app->status = 'rejected';
            $app->save();
        }
        return back();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}