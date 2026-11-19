<nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
    <div class="container">

        {{-- -lo manda a que navbar dependiendo de que sea --}}
        <a class="navbar-brand fw-bold" href="{{ Auth::check() ? (Auth::user()->hasRole('maestro') ? route('alumnos.index') : route('alumno.dashboard')) : url('/') }}">
            Control Escolar
        </a>

        @auth
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle text-light" href="#" role="button"
           data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ Auth::user()->name }}
            <span class="badge bg-secondary ms-1">
                {{ ucfirst(Auth::user()->getRoleNames()->first()) }}
            </span>
        </a>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Cerrar sesión
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </li>
@endauth



        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto">
                @auth
                    @role('maestro')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('alumnos.index') ? 'active fw-bold' : '' }}" href="{{ route('alumnos.index') }}">
                                Listado de Alumnos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('alumnos.create') ? 'active fw-bold' : '' }}" href="{{ route('alumnos.create') }}">
                                + Registrar Alumno
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('alumnos.evaluaciones') ? 'active fw-bold' : '' }}" href="{{ route('alumnos.evaluaciones') }}">
                                Registrar Faltas y Calificaciones
                            </a>
                        </li>
                    @endrole
                            {{-- -las cosas que hacen segun el rol --}}
                    @role('alumno')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('alumno.dashboard') ? 'active fw-bold' : '' }}" href="{{ route('alumno.dashboard') }}">
                                Mi Boleta
                            </a>
                        </li>
                    @endrole
                @endauth
            </ul>
            

            <ul class="navbar-nav ms-auto align-items-center">
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item me-3 text-light small">
                        {{ Auth::user()->name }}
                        <span class="badge bg-secondary ms-1">
                            {{ ucfirst(Auth::user()->getRoleNames()->first()) }}
                        </span>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                Cerrar sesión
                            </button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>