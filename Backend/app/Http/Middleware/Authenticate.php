<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            return route('login'); // Utilisé pour rediriger vers la route 'login' dans une application web
        }
    
        // Pour les API, renvoie un message d'erreur au lieu de rediriger
        return response()->json(['message' => 'Non authentifié'], 401);

        //return $request->expectsJson() ? null : route('login');
    }

    
}
