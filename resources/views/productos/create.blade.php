@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white fw-bold">Registrar Nuevo Producto</div>
                <div class="card-body">
                    <x-producto-form :categorias="$categorias" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection