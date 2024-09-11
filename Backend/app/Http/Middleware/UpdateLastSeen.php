<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $user->last_seen = now();
            $user->save();
        }

        return $next($request);
    }
}
