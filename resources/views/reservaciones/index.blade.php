<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservaciones</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

    <x-navbar />

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold mb-0">
                @role('admin')
                    Todas las Reservaciones
                @else
                    Mis Reservaciones
                @endrole
            </h2>

            <a href="{{ route('equipos.index') }}" class="btn btn-secondary">
                Volver a equipos
            </a>
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
                                <th>ID</th>
                                @role('admin')
                                    <th>Estudiante</th>
                                @endrole
                                <th>Código</th>
                                <th>Equipo</th>
                                <th>Fecha y hora</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reservaciones as $reservacion)
                                <tr>
                                    <td>{{ $reservacion->id }}</td>
                                    @role('admin')
                                        <td>{{ $reservacion->user->name }}</td>
                                    @endrole
                                    <td class="fw-bold">{{ $reservacion->equipo->codigo }}</td>
                                    <td>{{ $reservacion->equipo->nombre }}</td>
                                    <td>{{ $reservacion->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if ($reservacion->estado === 'Activa')
                                            <span class="badge bg-success">Activa</span>
                                        @else
                                            <span class="badge bg-secondary">Cancelada</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($reservacion->estado === 'Activa')
                                            <form method="POST" action="{{ route('reservaciones.cancelar', $reservacion->id) }}"
                                                  onsubmit="return confirm('¿Cancelar esta reservación y liberar el equipo?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    Cancelar reservación
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">Sin acciones</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        No hay reservaciones registradas.
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