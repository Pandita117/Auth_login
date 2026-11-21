<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('equipos.index') }}">
            Laboratorio de Cómputo
        </a>

        <div class="d-flex align-items-center gap-3">
            <a class="nav-link text-white {{ request()->routeIs('equipos.index') ? 'fw-bold text-decoration-underline' : '' }}"
               href="{{ route('equipos.index') }}">
                Equipos
            </a>

           

            <a class="nav-link text-white {{ request()->routeIs('reservaciones.index') ? 'fw-bold text-decoration-underline' : '' }}"
               href="{{ route('reservaciones.index') }}">
                @role('admin')
                    Reservaciones
                @else
                    Mis reservaciones
                @endrole
            </a>
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