<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('partidas.index') }}">
            Juego del Gato
        </a>

        <div class="d-flex align-items-center gap-3">
            <a class="nav-link text-white {{ request()->routeIs('partidas.index') ? 'fw-bold text-decoration-underline' : '' }}"
               href="{{ route('partidas.index') }}">
                Partidas
            </a>

           {{--  @role('jugador')
                <form action="{{ route('partidas.store') }}" method="POST" class="d-inline m-0">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">
                        + Nueva partida
                    </button>
                </form>
            @endrole --}}

            @role('admin')
                <a class="nav-link text-white {{ request()->routeIs('usuarios.*') ? 'fw-bold text-decoration-underline' : '' }}"
                   href="{{ route('usuarios.index') }}">
                    Usuarios
                </a>
            @endrole
        </div>

        <div class="d-flex align-items-center gap-3 text-white">
            <span class="small">
                <strong>{{ Auth::user()->name }}</strong>
                <span class="badge bg-info text-dark ms-1">{{ Auth::user()->getRoleNames()->first() }}</span>
            </span>

            <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</nav>