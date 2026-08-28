<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = Auth::user();

        if (! $usuario || ! $usuario->isAdmin()) {
            abort(403, 'Acceso restringido a administradores.');
        }

        return $next($request);
    }
}
