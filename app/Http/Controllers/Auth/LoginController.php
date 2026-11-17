<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/users';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        // 1. Si es Administrador -> va directo a su panel de administración
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        // 2. Si es Empleado (usuario normal) -> va directo al index de usuarios
        return redirect()->route('users.index');
    }
}