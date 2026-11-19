<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    //manda al master a ver tabla de alu y el alu a ver su dashboard
    protected function authenticated(Request $request, $user)
{
    if ($user->hasRole('maestro')) {
        return redirect()->route('alumnos.index');
    }

    if ($user->hasRole('alumno')) {
        return redirect()->route('alumno.dashboard');
    }

    return redirect($this->redirectPath());
}
}