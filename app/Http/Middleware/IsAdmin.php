<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Se o utilizador está logado E é admin, deixa passar
        if (auth()->check() && auth()->user()->is_admin == 1) {
            return $next($request);
        }

        // Se não for admin, devolve erro 403 (Proibido)
        abort(403, 'Acesso Negado. Apenas Administradores.');
    }
}