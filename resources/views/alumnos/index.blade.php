@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold">Listado de Alumnos</span>
            <a href="{{ route('alumnos.create') }}" class="btn btn-primary btn-sm">+ Registrar Alumno</a>
        </div>

        <div class="card-body">
            @if ($alumnos->isEmpty())
                <p class="text-muted mb-0">No hay alumnos registrados todavía.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Matrícula (ID)</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Calificación</th>
                                <th>Equivalencia</th>
                                <th>Faltas</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($alumnos as $alumno)
                                <tr>
                                    <td>{{ $alumno->id }}</td>
                                    <td>{{ $alumno->user->name }}</td>
                                    <td>{{ $alumno->user->email }}</td>
                                    <td>{{ $alumno->calificacion }}</td>
                                    <td><span class="badge bg-info text-dark">{{ $alumno->equivalencia }}</span></td>
                                    <td>{{ $alumno->faltas }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('alumnos.edit', $alumno->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                        <form action="{{ route('alumnos.destroy', $alumno->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este alumno?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection