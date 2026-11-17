<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorIfAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isAdmin() && !$request->session()->get('two_factor_verified')) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Debes completar la verificación de dos pasos para acceder.']);
        }

        return $next($request);
    }
}