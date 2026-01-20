<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RolUsuario
{
    public function handle(Request $request, Closure $next, string $rol): Response
    {
        $user = Auth::user();

        // Si no hay usuario autenticado
        if (!$user) {
            abort(403);
        }

        // Si el rol no coincide
        if ($user->tipo !== $rol) {
            abort(403);
        }

        return $next($request);
    }
}
