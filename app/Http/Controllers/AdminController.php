<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Muestra el formulario de 2FA (protegido por middleware 'signed')
    public function showVerifyForm(Request $request)
    {
        // Si ya verificó su 2FA en esta sesión, lo mandamos al dashboard
        if ($request->session()->get('two_factor_verified')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.autenti');
    }

    // Valida el código ingresado en el formulario
    public function verifyCode(Request $request)
    {
        $request->validate([
            'two_factor_code' => ['required', 'digits:6'],
        ]);

        $user = $request->user();

        if (
            !$user->two_factor_code ||
            $user->two_factor_code !== $request->two_factor_code ||
            now()->greaterThan($user->two_factor_expires_at)
        ) {
            return back()->withErrors([
                'two_factor_code' => 'El código es incorrecto o ha expirado.',
            ]);
        }

        // Limpiamos el código de la BD y marcamos la sesión como verificada
        $user->timestamps = false;
        $user->forceFill([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ])->save();

        $request->session()->put('two_factor_verified', true);

        return redirect()->route('admin.dashboard');
    }

    // Vista principal administrativa
    public function index()
    {
        return view('admin.dashboard');
    }
}