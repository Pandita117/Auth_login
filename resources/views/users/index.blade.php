@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Mensajes de alerta --}}
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabla de Usuarios Activos --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold">Gestión de Usuarios</span>
            @can('users.create')
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                    + Nuevo Usuario
                </a>
            @endcan
        </div>

        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo Electrónico</th>
                        <th>Rol</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                @foreach ($usuario->roles as $rol)
                                    <span class="badge {{ $rol->name === 'admin' ? 'bg-dark' : 'bg-info text-dark' }}">
                                        {{ $rol->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    @can('users.view')
                                        <a href="{{ route('users.show', $usuario->id) }}" class="btn btn-outline-secondary btn-sm">
                                            Ver
                                        </a>
                                    @endcan

                                    @can('users.edit')
                                        <a href="{{ route('users.edit', $usuario->id) }}" class="btn btn-outline-warning btn-sm">
                                            Editar
                                        </a>
                                    @endcan

                                    {{-- Eliminación Lógica (Soft Delete) - Solo Admin --}}
                                    @can('users.soft_delete')
                                        <form action="{{ route('users.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Deshabilitar este usuario (Soft Delete)?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                Deshabilitar
                                            </button>
                                        </form>
                                    @endcan

                                    {{-- Eliminación Permanente (Force Delete) - Solo Admin --}}
                                    @can('users.delete')
                                        <form action="{{ route('users.forceDelete', $usuario->id) }}" method="POST" onsubmit="return confirm('¡ATENCIÓN! Esto eliminará el registro permanentemente de la base de datos. ¿Continuar?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tabla de Usuarios Deshabilitados (Soft Deletes) - Visible solo si tiene permiso de restaurar --}}
    @can('users.restore')
        <div class="card shadow-sm border-secondary">
            <div class="card-header bg-secondary text-white fw-bold">
                Usuarios Deshabilitados (Soft Deletes)
            </div>
            <div class="card-body table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Fecha de deshabilitación</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuariosEliminados as $eliminado)
                            <tr>
                                <td>{{ $eliminado->id }}</td>
                                <td>{{ $eliminado->name }}</td>
                                <td>{{ $eliminado->email }}</td>
                                <td>{{ $eliminado->roles->pluck('name')->join(', ') }}</td>
                                <td>{{ $eliminado->deleted_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <form action="{{ route('users.restore', $eliminado->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm">
                                                Restaurar
                                            </button>
                                        </form>

                                        @can('users.delete')
                                            <form action="{{ route('users.forceDelete', $eliminado->id) }}" method="POST" onsubmit="return confirm('¿Eliminar definitivamente de la base de datos?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    Eliminar definitivo
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay usuarios deshabilitados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endcan
</div>
@endsection