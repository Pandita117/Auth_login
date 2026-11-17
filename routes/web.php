<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


// Panel exclusivo del Administrador
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    });

Route::get('/home', [HomeController::class, 'index'])
    ->name('home')
    ->middleware('auth');

// Rutas del CRUD protegidas únicamente por login y permisos de Spatie
Route::middleware(['auth'])->group(function () {
    // Listado (Empleado y Admin)
    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index')
        ->middleware('permission:users.view');

    // Crear (Empleado y Admin)
    Route::get('/users/create', [UserController::class, 'create'])
        ->name('users.create')
        ->middleware('permission:users.create');
    Route::post('/users', [UserController::class, 'store'])
        ->name('users.store')
        ->middleware('permission:users.create');

    // Ver detalle (Empleado y Admin)
    Route::get('/users/{user}', [UserController::class, 'show'])
        ->name('users.show')
        ->middleware('permission:users.view');

    // Editar (Empleado y Admin)
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
        ->name('users.edit')
        ->middleware('permission:users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])
        ->name('users.update')
        ->middleware('permission:users.edit');

    // Eliminación lógica - Soft Delete (Solo Admin)
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy')
        ->middleware('permission:users.soft_delete');

    // Restaurar usuario deshabilitado (Solo Admin)
    Route::patch('/users/{id}/restore', [UserController::class, 'restore'])
        ->name('users.restore')
        ->middleware('permission:users.restore');

    // Eliminación permanente - Force Delete (Solo Admin)
    Route::delete('/users/{id}/force-delete', [UserController::class, 'forceDelete'])
        ->name('users.forceDelete')
        ->middleware('permission:users.delete');
});