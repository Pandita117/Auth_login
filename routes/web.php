<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Middleware\EnsureAdminTwoFactor;
use App\Http\Controllers\ProductoController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/productos/create', [ProductoController::class, 'create'])->name('productos.create');
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::get('/productos/{producto}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{producto}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');
});

Route::middleware(['auth', 'role:admin|Empleado'])->group(function () {
    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



