<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verifica se o usuário está logado E se a 'role' dele é 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            // 2. Se for admin, deixa a requisição continuar
            return $next($request);
        }

        // 3. Se NÃO for admin (ou não estiver logado), bloqueia e redireciona
        //    para o dashboard de usuário comum com uma mensagem de erro.
        return redirect()->route('user.dashboard')->withErrors(['error' => 'Acesso não autorizado. Você não tem permissão para acessar esta página.']);
    }
}