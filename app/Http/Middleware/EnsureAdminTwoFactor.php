<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminTwoFactor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->isAdmin()) {
            abort(403, 'No tienes permisos administrativos.');
        }

        // Si es admin pero aún no ingresa su código 2FA, cerramos sesión o redirigimos
        if (!$request->session()->get('two_factor_verified')) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Debes completar la verificación de dos pasos.']);
        }

        return $next($request);
    }
}