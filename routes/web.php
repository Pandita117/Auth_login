<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Middleware\EnsureAdminTwoFactor;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\EnsureEmpleado;






Route::get('/home', [HomeController::class, 'index'])
    ->name('home')
    ->middleware(['auth', EnsureEmpleado::class]);


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



// Rutas del proceso 2FA (requiere estar autenticado)
Route::middleware(['auth'])->group(function () {
    // URL temporal
    Route::get('/admin/autenticar/{token}', [AdminController::class, 'showVerifyForm'])
        ->name('admin.autenti')
        ->middleware('signed');

    // Procesar el código ingresado
    Route::post('/admin/autenticar', [AdminController::class, 'verifyCode'])
        ->name('admin.autenti.verify');
});

// Rutas administrativas ya verificadas con 2FA
Route::middleware(['auth', EnsureAdminTwoFactor::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        // Aquí agregas el resto de tus rutas administrativas
    });