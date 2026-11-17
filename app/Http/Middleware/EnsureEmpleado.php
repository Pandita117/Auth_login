<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmpleado
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Si es administrador...
        if ($user->isAdmin()) {
            // Si ya completó su 2FA, lo mandamos a su propio panel
            if ($request->session()->get('two_factor_verified')) {
                return redirect()->route('admin.dashboard');
            }

            // Si NO ha completado su 2FA, cerramos sesión y lo regresamos al login
            // para evitar que se salte la verificación entrando a /home
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Debes completar la verificación de dos pasos para acceder.']);
        }

        // Si no tiene rol Empleado (ni admin), denegamos el acceso
        if (!$user->hasRole('Empleado')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
        
    }
}