@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-warning fw-bold">Editar equipo: {{ $equipo->codigo }}</div>
                <div class="card-body">
                    <x-equipo-form :equipo="$equipo" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection