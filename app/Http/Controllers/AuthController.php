<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Se for admin, vai para o painel de admin
            if (Auth::user()->is_admin) {
                return redirect()->intended('/admin');
            }
            
            // Se for tradutor, vai para o portal do tradutor
            if (Auth::user()->is_translator) {
                return redirect()->intended('/tradutor');
            }
            
            // Se for utilizador normal, vai para o perfil (ou uma página inicial)
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'E-mail ou palavra-passe incorretos.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}