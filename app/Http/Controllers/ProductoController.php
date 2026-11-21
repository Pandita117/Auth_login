<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $filtros = $request->only(['buscar', 'categoria_id', 'precio_min', 'precio_max', 'estado']);
        $orden   = $request->query('orden', 'fecha_desc');

        // Validar que per_page solo acepte 10, 15, 25 o 50
        $perPage = (int) $request->query('per_page', 10);
        if (!in_array($perPage, [10, 15, 25, 50])) {
            $perPage = 10;
        }

        $query = Producto::with('categoria')->filtrar($filtros);

        match ($orden) {
            'nombre_asc'       => $query->orderBy('producto', 'asc'),
            'nombre_desc'      => $query->orderBy('producto', 'desc'),
            'precio_desc'      => $query->orderBy('precio', 'desc'),
            'precio_asc'       => $query->orderBy('precio', 'asc'),
            'existencias_desc' => $query->orderBy('cantidad', 'desc'),
            'existencias_asc'  => $query->orderBy('cantidad', 'asc'),
            'fecha_asc'        => $query->orderBy('created_at', 'asc'),
            default            => $query->orderBy('created_at', 'desc'),
        };

        //filtros y paginate
        $productos  = $query->paginate($perPage)->withQueryString();
        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.index', compact('productos', 'categorias', 'orden', 'perPage'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('productos.create', compact('categorias'));
    }

    public function store(StoreProductoRequest $request)
    {
        Producto::create($request->validated());

        return redirect()->route('productos.index')
            ->with('status', 'Producto registrado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $producto->update($request->validated());

        return redirect()->route('productos.index')
            ->with('status', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('status', 'Producto eliminado correctamente.');
    }
}