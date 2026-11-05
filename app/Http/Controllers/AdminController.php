<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
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
            
            // Verifica se é admin antes de deixar entrar
            if (Auth::user()->is_admin == 1) {
                return redirect()->intended('/admin');
            }
            
            // Se não for admin, desloga e mostra erro
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
        $pagamentosPendentes = \App\Models\PaymentRequest::where('status', 'pending')->with('user')->get();
        $denuncias = \App\Models\Report::where('status', 'pending')->with(['reporter', 'reported'])->get();
        
        $candidaturas = \App\Models\TranslatorApplication::where('status', 'pending')->with('user')->get();
        $traducoesPendentes = \App\Models\Translation::where('status', 'pending')->with(['translator', 'language'])->get();

        return view('admin.dashboard', compact('totalUsers', 'totalMessages', 'users', 'pagamentosPendentes', 'denuncias', 'candidaturas', 'traducoesPendentes'));
    }

    // Aprovar Tradução e Pagar 0.50 MT
    public function handleTranslation($id, $action)
    {
        $translation = \App\Models\Translation::findOrFail($id);
        
        if ($translation->status !== 'pending') return back(); // Evita duplicar pagamentos

        if ($action === 'approve') {
            $translation->status = 'approved';
            $translation->save();

            // Paga 0.50 MT ao tradutor
            $wallet = \App\Models\TranslatorWallet::firstOrCreate(['user_id' => $translation->translator_id]);
            $wallet->balance += 0.50;
            $wallet->total_earned += 0.50;
            $wallet->save();
        } else {
            $translation->status = 'rejected';
            $translation->save();
        }
        return back();
    }
    
    // Função para aprovar ou rejeitar
    public function handlePayment(Request $request, $id)
    {
        $payment = \App\Models\PaymentRequest::findOrFail($id);
        $action = $request->input('action'); // 'approve' ou 'reject'

        if ($action === 'approve') {
            $payment->status = 'approved';
            $payment->save();

            // Ativa o Premium no utilizador por 30 dias
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
        
        // Impede suspender outro admin (ou a si mesmo)
        if ($user->is_admin) {
            return back()->withErrors(['msg' => 'Não podes suspender um administrador.']);
        }

        $user->is_suspended = !$user->is_suspended;
        $user->save();

        return back();
    }

    // 4. Terminar sessão
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }

       public function approveTranslator($id, $action)
    {
        $app = \App\Models\TranslatorApplication::findOrFail($id);
        
        if ($action === 'approve') {
            $app->status = 'approved';
            $app->save();
            
            $user = $app->user;
            $user->is_translator = true;
            
            // Garante que a língua é guardada como um Array (ex: [3])
            $langs = json_decode($app->language_requested, true);
            if (is_array($langs)) {
                $user->translator_languages = $langs;
            } else {
                $user->translator_languages = [$app->language_requested]; 
            }
            
            $user->save();

            \App\Models\TranslatorWallet::firstOrCreate(['user_id' => $user->id]);
        } else {
            $app->status = 'rejected';
            $app->save();
        }
        return back();
    }
}


