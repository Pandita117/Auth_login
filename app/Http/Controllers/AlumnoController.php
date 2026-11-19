<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AlumnoController extends Controller
{
    public function index ()
    {
        $alumno = Auth::user()->alumno;
        return view('alumnos.dashboard', compact('alumno'));
    }
}
