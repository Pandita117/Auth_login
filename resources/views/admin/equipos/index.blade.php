@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Equipos de Cómputo</h2>
            <p class="text-muted mb-0">
                Bienvenido, <strong>{{ Auth::user()->name }}</strong>
                <span class="badge bg-info text-dark">{{ Auth::user()->getRoleNames()->first() }}</span>
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('reservaciones.index') }}" class="btn btn-outline-primary">
                @role('Administrador')
                    Ver todas las reservaciones
                @else
                    Mis reservaciones
                @endrole
            </a>

            @role('Administrador')
                <a href="{{ route('equipos.create') }}" class="btn btn-primary">
                    + Nuevo equipo
                </a>
            @endrole
        </div>
    </div>

    @if (session('mensaje'))
        <div class="alert alert-success">{{ session('mensaje') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($equipos as $equipo)
                            <tr>
                                <td class="fw-bold">{{ $equipo->codigo }}</td>
                                <td>{{ $equipo->nombre }}</td>
                                <td>{{ $equipo->descripcion ?? 'Sin descripción' }}</td>
                                <td>
                                    @if ($equipo->estado === 'Disponible')
                                        <span class="badge bg-success">Disponible</span>
                                    @else
                                        <span class="badge bg-danger">Ocupado</span>
                                    @endif
                                </td>
                                <td class="text-center text-nowrap">
                                    @role('Administrador')
                                        <a href="{{ route('equipos.edit', $equipo->id) }}" class="btn btn-warning btn-sm">
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('equipos.destroy', $equipo->id) }}" class="d-inline"
                                              onsubmit="return confirm('¿Eliminar este equipo?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                        </form>
                                    @endrole

                                    @role('Estudiante')
                                        @if ($equipo->estado === 'Disponible')
                                            <form method="POST" action="{{ route('reservaciones.store', $equipo->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    Reservar equipo
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">No disponible</span>
                                        @endif
                                    @endrole
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No hay equipos disponibles en este momento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection