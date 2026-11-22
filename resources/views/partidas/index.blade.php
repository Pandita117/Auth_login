<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partidas - Gato</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

    <x-navbar />

    <div class="container">
        @if (session('mensaje'))
            <div class="alert alert-success">{{ session('mensaje') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- VISTA DEL JUGADOR --}}
        @role('jugador')
            <div class="row g-4 mb-4">
                {{-- Partidas disponibles para unirse --}}
                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center">
                            <span>Partidas disponibles</span>
                            <form action="{{ route('partidas.store') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-light btn-sm fw-bold">+ Crear partida</button>
                            </form>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Creador (X)</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($disponibles as $p)
                                        <tr>
                                            <td>{{ $p->id }}</td>
                                            <td>{{ $p->jugadorX->name }}</td>
                                            <td><span class="badge bg-warning text-dark">{{ $p->estado }}</span></td>
                                            <td class="text-end">
                                                <form action="{{ route('partidas.unirse', $p->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">Unirse (O)</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No hay partidas esperando rival.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Lista de jugadores disponibles --}}
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-dark text-white fw-bold">Jugadores registrados</div>
                        <ul class="list-group list-group-flush">
                            @forelse ($jugadores as $j)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $j->name }}
                                    <span class="badge bg-success">Jugador</span>
                                </li>
                            @empty
                                <li class="list-group-item text-muted">No hay otros jugadores.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Mis partidas / Historial --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white fw-bold">Mis partidas e historial</div>
                <div class="card-body">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Jugador X</th>
                                <th>Jugador O</th>
                                <th>Estado</th>
                                <th>Resultado</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($misPartidas as $p)
                                <tr>
                                    <td>{{ $p->id }}</td>
                                    <td>{{ $p->jugadorX->name }}</td>
                                    <td>{{ $p->jugadorO->name ?? 'Pendiente' }}</td>
                                    <td>{{ $p->estado }}</td>
                                    <td>{{ $p->resultado ?? '-' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('partidas.show', $p->id) }}" class="btn btn-primary btn-sm">
                                            {{ in_array($p->estado, ['Esperando', 'En curso']) ? 'Continuar' : 'Ver tablero' }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Aún no has jugado ninguna partida.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endrole

        {{-- VISTA DEL ADMINISTRADOR --}}
        @role('admin')
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white fw-bold">Historial general de partidas</div>
                <div class="card-body">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Jugador X</th>
                                <th>Jugador O</th>
                                <th>Estado</th>
                                <th>Resultado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($partidas as $p)
                                <tr>
                                    <td>{{ $p->id }}</td>
                                    <td>{{ $p->jugadorX->name }}</td>
                                    <td>{{ $p->jugadorO->name ?? 'Sin rival' }}</td>
                                    <td>{{ $p->estado }}</td>
                                    <td>{{ $p->resultado ?? '-' }}</td>
                                    <td class="text-center text-nowrap">
                                        <a href="{{ route('partidas.show', $p->id) }}" class="btn btn-info btn-sm">Ver</a>
                                        @if (in_array($p->estado, ['Esperando', 'En curso']))
                                            <form action="{{ route('partidas.cancelar', $p->id) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('¿Cancelar esta partida en curso?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-danger btn-sm">Cancelar</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No hay partidas registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endrole
    </div>

</body>
</html>