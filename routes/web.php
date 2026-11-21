<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\ReservacionController;
use App\Http\Middleware\EnsureAdminTwoFactor;
use Illuminate\Support\Facades\Auth;



//si esta autenficiaco mandalo ala vista principal si no mandalo a login 
Route::get('/', function () {
    return Auth::check() ? redirect()->route('equipos.index') : redirect()->route('login');
});

Auth::routes();


//rutas del admin
Route::middleware(['auth','role:admin'])->group(function(){
    Route::get('/equipos.create',[EquipoController::class,'create'])->name('equipos.create');
    Route::post('/equipos.store',[EquipoController::class,'strore'])->name('equipos.store');
    Route::get('/equipos.{equipo}/edit',[EquipoController::class,'edit'])->name('equipos.edit');
    Route::put('/equipos{equipo}',[EquipoController::class,'update'])->name('equipo.edit');
    Route::delete('/equipos{equipo}',[EquipoController::class,'destroy'])->name('equipos.destroy');
});


//ruta de estudiante 
Route::middleware(['auth', 'role:estudiante'])->group(function(){
    Route::post('/reservaciones/{equipo}',[ReservacionController::class,'store'])->name('reservaciones.store');
});

//rutas compartidas de pero llevan a distintas dependiendo del rol 
Route::middleware(['auth', 'role:admin|estudiante'])->group(function () {
    Route::get('/equipos', [EquipoController::class, 'index'])->name('equipos.index');
    Route::get('/reservaciones', [ReservacionController::class, 'index'])->name('reservaciones.index');
    //ambos pueden cancelas las reservaciones epero el estudiante solo las suyas
    Route::patch('/reservaciones/{reservacion}/cancelar', [ReservacionController::class, 'cancelar'])->name('reservaciones.cancelar');
});