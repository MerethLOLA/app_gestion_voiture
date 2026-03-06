<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté.');
        }

        // Vérifier si l'utilisateur a la permission
        if (!$user->hasPermission($permission)) {
            abort(403, "Accès refusé. Vous n'avez pas la permission requise : {$permission}");
        }

        return $next($request);
    }
}
