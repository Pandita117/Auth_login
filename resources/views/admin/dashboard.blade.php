@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Panel de Administración</span>
                    <span class="badge bg-dark">Rol: Administrador</span>
                </div>

                <div class="card-body">
                    <h5 class="card-title">Bienvenido, {{ Auth::user()->name }}</h5>
                    <p class="card-text text-muted">
                        Desde este panel tienes acceso total al sistema, incluyendo la administración de usuarios, deshabilitación (Soft Delete), restauración y eliminación permanente.
                    </p>

                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('users.index') }}" class="btn btn-primary">
                            Gestionar Usuarios (CRUD)
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection