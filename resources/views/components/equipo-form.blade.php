@props(['equipo' => null])

<form method="POST" action="{{ $equipo ? route('equipos.update', $equipo->id) : route('equipos.store') }}" novalidate>
    @csrf
    @if ($equipo)
        @method('PUT')
    @endif

    <div class="mb-3">
        <label for="codigo" class="form-label">Código del equipo</label>
        <input type="text" id="codigo" name="codigo"
               class="form-control @error('codigo') is-invalid @enderror"
               placeholder="Ej. PC-01"
               value="{{ old('codigo', $equipo?->codigo) }}" autofocus>
        @error('codigo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre del equipo</label>
        <input type="text" id="nombre" name="nombre"
               class="form-control @error('nombre') is-invalid @enderror"
               placeholder="Ej. Dell OptiPlex 7090"
               value="{{ old('nombre', $equipo?->nombre) }}">
        @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción (Opcional)</label>
        <textarea id="descripcion" name="descripcion" rows="3"
                  class="form-control @error('descripcion') is-invalid @enderror"
                  placeholder="Ej. Core i7, 16GB RAM, SSD 512GB">{{ old('descripcion', $equipo?->descripcion) }}</textarea>
        @error('descripcion')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="estado" class="form-label">Estado</label>
        <select id="estado" name="estado" class="form-select @error('estado') is-invalid @enderror">
            <option value="Disponible" {{ old('estado', $equipo?->estado) === 'Disponible' ? 'selected' : '' }}>
                Disponible
            </option>
            <option value="Ocupado" {{ old('estado', $equipo?->estado) === 'Ocupado' ? 'selected' : '' }}>
                Ocupado
            </option>
        </select>
        @error('estado')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('equipos.index') }}" class="btn btn-secondary">Regresar</a>
        <button type="submit" class="btn btn-primary">
            {{ $equipo ? 'Actualizar equipo' : 'Registrar equipo' }}
        </button>
    </div>
</form>