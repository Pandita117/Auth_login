<?php

use App\Http\Controllers\PartidaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('partidas.index') : redirect()->route('login');
});

Auth::routes();

// rutas compartidas
Route::middleware(['auth', 'role:admin|jugador'])->group(function () {
    Route::get('/partidas', [PartidaController::class, 'index'])->name('partidas.index');
    Route::get('/partidas/{partida}', [PartidaController::class, 'show'])->name('partidas.show');
    Route::get('/partidas/{partida}/estado', [PartidaController::class, 'estado'])->name('partidas.estado');
});

// rutas del jugador
Route::middleware(['auth', 'role:jugador'])->group(function () {
    Route::post('/partidas', [PartidaController::class, 'store'])->name('partidas.store');
    Route::post('/partidas/{partida}/unirse', [PartidaController::class, 'unirse'])->name('partidas.unirse');
    Route::post('/partidas/{partida}/mover', [PartidaController::class, 'mover'])->name('partidas.mover');
    Route::patch('/partidas/{partida}/abandonar', [PartidaController::class, 'abandonar'])->name('partidas.abandonar');
});

// rutas exclusivas del admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::patch('/partidas/{partida}/cancelar', [PartidaController::class, 'cancelarAdmin'])->name('partidas.cancelar');
    Route::resource('usuarios', UserController::class)->except(['show']);
});