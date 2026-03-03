<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Vérifie si l'utilisateur est admin ou redirige
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Vérifier si l'utilisateur est admin
        if (Auth::user()->is_admin == 1) {
            return $next($request);
        }

        // 3. Si utilisateur simple tente d'accéder à /admin, on le renvoie au dashboard membre
        return redirect()->route('dashboard')->with('error', 'Accès réservé aux administrateurs.');
    }
}