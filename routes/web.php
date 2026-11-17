<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Middleware\EnsureAdminTwoFactor;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\EnsureEmpleado;
use App\Http\Controllers\UserController;







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


    //rutas para el controlador de usuarios

Route::middleware(['auth'])->group(function () {
    // Listado y detalle (Empleado y Admin)
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

    // Ver detalle (va después de /users/create para no colisionar con {user})
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