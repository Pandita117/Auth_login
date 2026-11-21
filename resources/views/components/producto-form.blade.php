@props(['producto' => null, 'categorias'])

<form method="POST" action="{{ $producto ? route('productos.update', $producto->id) : route('productos.store') }}" novalidate>
    @csrf
    @if ($producto)
        @method('PUT')
    @endif

    <div class="mb-3">
        <label for="producto" class="form-label">Nombre del producto</label>
        <input id="producto" type="text" name="producto"
               class="form-control @error('producto') is-invalid @enderror"
               value="{{ old('producto', $producto?->producto) }}" autofocus>
        @error('producto')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="3"
                  class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $producto?->descripcion) }}</textarea>
        @error('descripcion')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="categoria_id" class="form-label">Categoría</label>
            <select id="categoria_id" name="categoria_id"
                    class="form-select @error('categoria_id') is-invalid @enderror">
                <option value="">-- Selecciona una categoría --</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}"
                        {{ (string) old('categoria_id', $producto?->categoria_id) === (string) $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
            @error('categoria_id')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select id="estado" name="estado"
                    class="form-select @error('estado') is-invalid @enderror">
                <option value="">-- Selecciona un estado --</option>
                <option value="Disponible" {{ old('estado', $producto?->estado) === 'Disponible' ? 'selected' : '' }}>
                    Disponible
                </option>
                <option value="Agotado" {{ old('estado', $producto?->estado) === 'Agotado' ? 'selected' : '' }}>
                    Agotado
                </option>
            </select>
            @error('estado')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="precio" class="form-label">Precio ($)</label>
            <input id="precio" type="text" name="precio"
                   class="form-control @error('precio') is-invalid @enderror"
                   value="{{ old('precio', $producto?->precio) }}">
            @error('precio')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="cantidad" class="form-label">Existencias</label>
            <input id="cantidad" type="text" name="cantidad"
                   class="form-control @error('cantidad') is-invalid @enderror"
                   value="{{ old('cantidad', $producto?->cantidad ?? 0) }}">
            @error('cantidad')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="d-flex justify-content-between mt-3">
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">
            {{ $producto ? 'Actualizar Producto' : 'Registrar Producto' }}
        </button>
    </div>
</form>