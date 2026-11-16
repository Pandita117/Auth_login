@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Panel de Administración</span>
                    <span class="badge bg-success">2FA Verificado</span>
                </div>

                <div class="card-body">
                    <h5 class="card-title">Bienvenido, {{ Auth::user()->name }}</h5>
                    <p class="card-text text-muted">
                        Has iniciado sesión y verificado tu identidad administrativa correctamente.
                    </p>

                    <hr>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection