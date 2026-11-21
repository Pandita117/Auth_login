@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <span class="fw-bold">Catálogo de Productos</span>

            @role('admin')
                <a href="{{ route('productos.create') }}" class="btn btn-success btn-sm">
                    + Nuevo Producto
                </a>
            @endrole
        </div>

        <div class="card-body">
            {{-- Formulario de Filtros, Ordenamiento y Paginación --}}
            <form method="GET" action="{{ route('productos.index') }}" class="border rounded p-3 mb-4 bg-light" novalidate>
                <div class="row g-2">
                    <div class="col-md-3">
                        <label for="buscar" class="form-label small fw-bold mb-1">Buscar por nombre</label>
                        <input type="text" id="buscar" name="buscar" class="form-control form-control-sm"
                               placeholder="Ej. Laptop..." value="{{ request('buscar') }}">
                    </div>

                    <div class="col-md-3">
                        <label for="categoria_id" class="form-label small fw-bold mb-1">Categoría</label>
                        <select id="categoria_id" name="categoria_id" class="form-select form-select-sm">
                            <option value="">Todas las categorías</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}"
                                    {{ (string) request('categoria_id') === (string) $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="precio_min" class="form-label small fw-bold mb-1">Precio mínimo</label>
                        <input type="text" id="precio_min" name="precio_min" class="form-control form-control-sm"
                               placeholder="0.00" value="{{ request('precio_min') }}">
                    </div>

                    <div class="col-md-2">
                        <label for="precio_max" class="form-label small fw-bold mb-1">Precio máximo</label>
                        <input type="text" id="precio_max" name="precio_max" class="form-control form-control-sm"
                               placeholder="9999.99" value="{{ request('precio_max') }}">
                    </div>

                    <div class="col-md-2">
                        <label for="estado" class="form-label small fw-bold mb-1">Estado</label>
                        <select id="estado" name="estado" class="form-select form-select-sm">
                            <option value="">Todos</option>
                            <option value="Disponible" {{ request('estado') === 'Disponible' ? 'selected' : '' }}>
                                Disponible
                            </option>
                            <option value="Agotado" {{ request('estado') === 'Agotado' ? 'selected' : '' }}>
                                Agotado
                            </option>
                        </select>
                    </div>
                </div>

                <div class="row g-2 align-items-end mt-2">
                    <div class="col-md-4">
                        <label for="orden" class="form-label small fw-bold mb-1">Ordenar por</label>
                        <select id="orden" name="orden" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="fecha_desc" {{ $orden === 'fecha_desc' ? 'selected' : '' }}>Fecha: Más recientes</option>
                            <option value="fecha_asc" {{ $orden === 'fecha_asc' ? 'selected' : '' }}>Fecha: Más antiguos</option>
                            <option value="nombre_asc" {{ $orden === 'nombre_asc' ? 'selected' : '' }}>Nombre: A - Z</option>
                            <option value="nombre_desc" {{ $orden === 'nombre_desc' ? 'selected' : '' }}>Nombre: Z - A</option>
                            <option value="precio_desc" {{ $orden === 'precio_desc' ? 'selected' : '' }}>Precio: Mayor a menor</option>
                            <option value="precio_asc" {{ $orden === 'precio_asc' ? 'selected' : '' }}>Precio: Menor a mayor</option>
                            <option value="existencias_desc" {{ $orden === 'existencias_desc' ? 'selected' : '' }}>Existencias: Mayor a menor</option>
                            <option value="existencias_asc" {{ $orden === 'existencias_asc' ? 'selected' : '' }}>Existencias: Menor a mayor</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="per_page" class="form-label small fw-bold mb-1">Mostrar registros</label>
                        <select id="per_page" name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                            @foreach ([10, 15, 25, 50] as $cantidad)
                                <option value="{{ $cantidad }}" {{ $perPage === $cantidad ? 'selected' : '' }}>
                                    {{ $cantidad }} por página
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-5 d-flex justify-content-md-end gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            Aplicar filtros
                        </button>
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary btn-sm">
                            Limpiar filtros
                        </a>
                    </div>
                </div>
            </form>

            {{-- Tabla de Productos --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Existencias</th>
                            <th>Estado</th>
                            <th>Fecha de registro</th>
                            @role('admin')
                                <th class="text-center">Acciones</th>
                            @endrole
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productos as $producto)
                            <tr>
                                <td>{{ $producto->id }}</td>
                                <td class="fw-semibold">{{ $producto->producto }}</td>
                                <td>{{ Str::limit($producto->descripcion, 50) }}</td>
                                <td>{{ $producto->categoria->nombre }}</td>
                                <td>${{ number_format($producto->precio, 2) }}</td>
                                <td>{{ $producto->cantidad }}</td>
                                <td>
                                    @if ($producto->estado === 'Disponible')
                                        <span class="badge bg-success">Disponible</span>
                                    @else
                                        <span class="badge bg-danger">Agotado</span>
                                    @endif
                                </td>
                                <td>{{ $producto->created_at->format('d/m/Y H:i') }}</td>

                                @role('admin')
                                    <td class="text-center text-nowrap">
                                        <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning btn-sm">
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('productos.destroy', $producto->id) }}" class="d-inline"
                                              onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                @endrole
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    No se encontraron productos con los filtros seleccionados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Información de Paginación --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 gap-2">
                <p class="text-muted small mb-0">
                    Página <strong>{{ $productos->currentPage() }}</strong> de <strong>{{ $productos->lastPage() }}</strong>
                    — Total de registros encontrados: <strong>{{ $productos->total() }}</strong>
                </p>
                <div>
                    {{ $productos->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection