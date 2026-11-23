<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;



class EquipoController extends Controller
{

    public function index()
    {

    
    $equipos = Auth::user()->hasRole('admin')
    ? Equipo::orderBy('codigo')->get()
    : Equipo::where('estado', 'Disponible')->orderBy('codigo')->get();

    return view('equipos.index',compact('equipos'));
    }


    public function create()
    {
        return view ('equipos.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'codigo' => 'required|string|max:50|unique:equipos,codigo',
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:Disponible,Ocupado',
            
        ],
        [
            'codigo.required' => 'El codigo del equipo es obligatorio',
            'codigo.unique' => 'El codigo del equipo ya esta regitrado',
            'nombre.required' => 'El nombre del equipo es obligatorio',
        ]);

        Equipo::create($datos);

        return redirect()->route('equipos.index')
        ->with('mensaje', 'Equipo registrado correctamente');
    }

    public function edit(Equipo $equipo)
    {
        return view('equipos.edit', compact('equipo'));
    }

   public function update(Request $request, Equipo $equipo)
{
    $datos = $request->validate([
        'codigo' => 'required|string|max:50|unique:equipos,codigo,' . $equipo->id,
        'nombre' => 'required|string|max:150',
        'descripcion' => 'nullable|string',
        'estado' => 'required|in:Disponible,Ocupado'
    ],
    [
        'codigo.required' => 'El codigo del equipo es obligatorio',
        'codigo.unique' => 'El codigo del equipo ya esta regitrado',
        'nombre.required' => 'El nombre del equipo es obligatorio',
    ]);

    DB::transaction(function () use ($equipo, $datos) {
        $equipo->update($datos);

        // admin regresa equipo en edicion a disponivle cambiando el estado a cancelada
        if ($datos['estado'] === 'Disponible') {
            $equipo->reservaciones()
                ->where('estado', 'Activa')
                ->update(['estado' => 'Cancelada']);
        }
    });

    return redirect()->route('equipos.index')
        ->with('mensaje', 'Equipo actualizado correctamente');
}


   public function destroy(Equipo $equipo)
{
    if ($equipo->reservaciones()->exists()) {
        return back()->with('error', 'No se puede eliminar el equipo porque tiene reservaciones registradas.');
    }

    $equipo->delete();

    return redirect()->route('equipos.index')
        ->with('mensaje', 'Equipo eliminado correctamente.');
}



    
}

