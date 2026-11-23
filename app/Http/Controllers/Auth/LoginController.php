<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/partidas';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
/* 
    protected function authenticated(Request $request, $user)
    {
        if ($user->hasRole('maestro')) {
            return redirect()->route('maestro.dashboard');
        }

        if ($user->hasRole('alumno')) {
            return redirect()->route('alumno.dashboard');
        }

        return redirect($this->redirectPath());
    } */
}