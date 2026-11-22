<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Partida #{{ $partida->id }} - Gato</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .tablero {
            display: grid;
            grid-template-columns: repeat(3, 100px);
            grid-template-rows: repeat(3, 100px);
            gap: 8px;
            justify-content: center;
        }
        .casilla {
            font-size: 2.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-light">

    <x-navbar />

    @php
        $miSimbolo = null;
        if ($partida->jugador_x_id === Auth::id()) $miSimbolo = 'X';
        elseif ($partida->jugador_o_id === Auth::id()) $miSimbolo = 'O';
    @endphp

    <div class="container text-center">
        <div class="card shadow-sm border-0 mx-auto" style="max-width: 500px;">
            <div class="card-header bg-dark text-white fw-bold">
                Partida #{{ $partida->id }}
            </div>
            <div class="card-body">

                <div class="d-flex justify-content-between mb-3 px-3">
                    <div>
                        <span class="badge bg-primary">Jugador X</span><br>
                        <strong>{{ $partida->jugadorX->name }}</strong>
                    </div>
                    <div>
                        <span class="badge bg-danger">Jugador O</span><br>
                        <strong id="nombre-jugador-o">{{ $partida->jugadorO->name ?? 'Esperando rival...' }}</strong>
                    </div>
                </div>

                @if ($miSimbolo)
                    <p class="mb-2">Tu ficha es: <span class="badge bg-dark fs-6">{{ $miSimbolo }}</span></p>
                @endif

                <div id="banner-estado" class="alert alert-info fw-bold">
                    @if ($partida->estado === 'Esperando')
                        Esperando a que se una otro jugador...
                    @elseif ($partida->estado === 'En curso')
                        Turno actual: {{ $partida->turno }}
                    @else
                        Partida terminada: {{ $partida->resultado }}
                    @endif
                </div>

                <div id="alerta-error" class="alert alert-danger d-none"></div>

                <div class="tablero my-4">
                    @foreach ($partida->tablero as $indice => $valor)
                        <button type="button"
                                class="btn btn-outline-dark casilla"
                                data-index="{{ $indice }}"
                                onclick="hacerMovimiento({{ $indice }})">
                            {{ $valor }}
                        </button>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center gap-2 mt-3">
                    <a href="{{ route('partidas.index') }}" class="btn btn-secondary">Volver al inicio</a>

                    @if ($miSimbolo && in_array($partida->estado, ['Esperando', 'En curso']))
                        <form method="POST" action="{{ route('partidas.abandonar', $partida->id) }}"
                              onsubmit="return confirm('¿Seguro que deseas abandonar la partida? Perderás automáticamente.');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-danger">Abandonar partida</button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <script>
        const partidaId = {{ $partida->id }};
        const miSimbolo = @json($miSimbolo);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function hacerMovimiento(casilla) {
            fetch(`/partidas/${partidaId}/mover`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ casilla: casilla })
            })
            .then(res => res.json())
            .then(data => {
                const alerta = document.getElementById('alerta-error');
                if (!data.ok) {
                    alerta.textContent = data.mensaje;
                    alerta.classList.remove('d-none');
                } else {
                    alerta.classList.add('d-none');
                    actualizarTablero();
                }
            });
        }

        function actualizarTablero() {
            fetch(`/partidas/${partidaId}/estado`)
                .then(res => res.json())
                .then(data => {
                    if (data.jugador_o) {
                        document.getElementById('nombre-jugador-o').textContent = data.jugador_o;
                    }

                    const botones = document.querySelectorAll('.casilla');
                    data.tablero.forEach((valor, i) => {
                        botones[i].textContent = valor;
                        botones[i].disabled = (valor !== '' || data.estado !== 'En curso' || data.turno !== miSimbolo);
                    });

                    const banner = document.getElementById('banner-estado');
                    if (data.estado === 'Esperando') {
                        banner.className = 'alert alert-warning fw-bold';
                        banner.textContent = 'Esperando a que se una otro jugador...';
                    } else if (data.estado === 'En curso') {
                        const esMiTurno = (data.turno === miSimbolo);
                        banner.className = esMiTurno ? 'alert alert-success fw-bold' : 'alert alert-secondary fw-bold';
                        banner.textContent = esMiTurno ? `¡Es tu turno (${data.turno})!` : `Turno del rival (${data.turno})...`;
                    } else {
                        banner.className = 'alert alert-primary fw-bold';
                        banner.textContent = `Partida terminada: ${data.resultado}`;
                        clearInterval(intervalo);
                    }
                });
        }

        actualizarTablero();
        const intervalo = setInterval(actualizarTablero, 2000);
    </script>

</body>
</html>