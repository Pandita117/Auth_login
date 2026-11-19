@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Editar Alumno: {{ $alumno->user->name }}</div>
                <div class="card-body">
                    <x-alumno-form :alumno="$alumno" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection