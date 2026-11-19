<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\MaestroController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

// Rutas del Maestro (su página inicial es el listado de alumnos: alumnos.index)
Route::middleware(['auth', 'role:maestro'])->group(function () {
    Route::resource('alumnos', MaestroController::class)->except(['show']);

    Route::get('/evaluaciones', [MaestroController::class, 'evaluaciones'])->name('alumnos.evaluaciones');
    Route::post('/evaluaciones', [MaestroController::class, 'guardarEvaluaciones'])->name('alumnos.evaluaciones.guardar');
});

// Rutas del Alumno
Route::middleware(['auth', 'role:alumno'])
    ->prefix('alumno')
    ->name('alumno.')
    ->group(function () {
        Route::get('/dashboard', [AlumnoController::class, 'index'])->name('dashboard');
    });