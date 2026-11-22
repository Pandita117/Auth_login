<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

    <x-navbar />

    <div class="container">
        <div class="card shadow-sm border-0 mx-auto" style="max-width: 500px;">
            <div class="card-header bg-warning fw-bold">Editar usuario: {{ $usuario->name }}</div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $usuario->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nueva contraseña (dejar vacío para no cambiar)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rol</label>
                        <select name="rol" class="form-select">
                            <option value="jugador" {{ $usuario->hasRole('jugador') ? 'selected' : '' }}>Jugador</option>
                            <option value="admin" {{ $usuario->hasRole('admin') ? 'selected' : '' }}>Administrador</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>