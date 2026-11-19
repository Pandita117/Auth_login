@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Mi Boleta </div>
                <div class="card-body">
                    {{-- -si no hay deste --}}
                    @if (!$alumno)
                        <div class="alert alert-warning mb-0">Aún no tienes un perfil académico asignado.</div>
                    @else
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Matrícula:</strong>
                                <span>{{ $alumno->id }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Nombre:</strong>
                                <span>{{ Auth::user()->name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Calificación Final:</strong>
                                <span class="fs-5 fw-bold">{{ $alumno->calificacion }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Equivalencia Alfabética:</strong>
                                <span class="badge bg-primary fs-6">{{ $alumno->equivalencia }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Faltas Acumuladas:</strong>
                                <span class="badge bg-danger fs-6">{{ $alumno->faltas }}</span>
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection