<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipos de Cómputo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

    <x-navbar />

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold mb-0">
                @role('admin')
                    Administración de Equipos
                @else
                    Equipos Disponibles
                @endrole
            </h2>

            @role('admin')
                <a href="{{ route('equipos.create') }}" class="btn btn-primary">
                    + Nuevo equipo
                </a>
            @endrole
        </div>

        @if (session('mensaje'))
            <div class="alert alert-success">{{ session('mensaje') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm border-0">
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
                                        @role('admin')
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

                                        @role('estudiante')
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

</body>
</html>