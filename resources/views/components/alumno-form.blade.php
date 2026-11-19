{{-- Detecta si se está editando o creando un nuevo alumno --}}
@props(['alumno' => null])

<form method="POST" action="{{ $alumno ? route('alumnos.update', $alumno->id) : route('alumnos.store') }}">
    @csrf
    @if ($alumno)
        @method('PUT')
    @endif

    <div class="mb-3">
        <label for="name" class="form-label">Nombre completo</label>
        <input id="name" type="text" name="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $alumno?->user->name) }}" required autofocus>
        @error('name')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Correo electrónico</label>
        <input id="email" type="email" name="email"
               class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $alumno?->user->email) }}" required>
        @error('email')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    {{-- Solo aparece al editar al alumno, no al registrarlo por primera vez --}}
    @if ($alumno)
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="calificacion" class="form-label">Calificación (0 - 100)</label>
                <input id="calificacion" type="number" step="0.01" min="0" max="100" name="calificacion"
                       class="form-control @error('calificacion') is-invalid @enderror"
                       value="{{ old('calificacion', $alumno->calificacion) }}" required>
                @error('calificacion')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="faltas" class="form-label">Faltas</label>
                <input id="faltas" type="number" min="0" name="faltas"
                       class="form-control @error('faltas') is-invalid @enderror"
                       value="{{ old('faltas', $alumno->faltas) }}" required>
                @error('faltas')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <hr>
        <p class="text-muted small">Deja estos campos en blanco si no deseas cambiar la contraseña del alumno.</p>
    @endif

    <div class="mb-3">
        <label for="password" class="form-label">{{ $alumno ? 'Nueva contraseña (opcional)' : 'Contraseña' }}</label>
        <input id="password" type="password" name="password"
               class="form-control @error('password') is-invalid @enderror"
               {{ $alumno ? '' : 'required' }}>
        @error('password')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
        <input id="password_confirmation" type="password" name="password_confirmation"
               class="form-control" {{ $alumno ? '' : 'required' }}>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">
            {{ $alumno ? 'Actualizar Alumno' : 'Registrar Alumno' }}
        </button>
    </div>
</form>