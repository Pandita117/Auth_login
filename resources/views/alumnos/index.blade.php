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
            <form method="GET" action="{{ route('alumnos.index') }}" class="row g-2 align-items-center mb-3">
    <div class="col-auto">
        <label for="orden" class="col-form-label fw-bold text-muted small">Ordenar por:</label>
    </div>
    <div class="col-auto">
        <select name="orden" id="orden" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="id_asc" {{ $orden === 'id_asc' ? 'selected' : '' }}>
                Matrícula (Por defecto)
            </option>

            <optgroup label="Alfabéticamente (Nombre)">
                <option value="nombre_asc" {{ $orden === 'nombre_asc' ? 'selected' : '' }}>
                    Nombre: A - Z
                </option>
                <option value="nombre_desc" {{ $orden === 'nombre_desc' ? 'selected' : '' }}>
                    Nombre: Z - A
                </option>
            </optgroup>

            <optgroup label="Por Calificación">
                <option value="calificacion_desc" {{ $orden === 'calificacion_desc' ? 'selected' : '' }}>
                    Calificación: Mayor a menor
                </option>
                <option value="calificacion_asc" {{ $orden === 'calificacion_asc' ? 'selected' : '' }}>
                    Calificación: Menor a mayor
                </option>
            </optgroup>

            <optgroup label="Por Faltas">
                <option value="faltas_desc" {{ $orden === 'faltas_desc' ? 'selected' : '' }}>
                    Faltas: Mayor a menor
                </option>
                <option value="faltas_asc" {{ $orden === 'faltas_asc' ? 'selected' : '' }}>
                    Faltas: Menor a mayor
                </option>
            </optgroup>
        </select>
    </div>

    @if ($orden !== 'id_asc')
        <div class="col-auto">
            <a href="{{ route('alumnos.index') }}" class="btn btn-outline-secondary btn-sm">
                Restablecer
            </a>
        </div>
    @endif
</form>
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