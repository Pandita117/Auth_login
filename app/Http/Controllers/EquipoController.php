<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;


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
        return view ('equipos.create',compact('equipos'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'codigo' => 'required|string|max:50|unique:equipos,codigo',
            'nombre' => 'required|string1max:150',
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
            'codigo' => 'required|string|max:50|unique|:equipos,codigo,' . $equipo->id,
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:Disponible,Ocupado'
        ],

        [
            'codigo.required' => 'El codigo del equipo es obligatorio',
            'codigo.unique' => 'El codigo del equipo ya esta regitrado',
            'nombre.required' => 'El nombre del equipo es obligatorio',
        ]);

        $equipo->update($datos);

        return redirect()->route('equipos.index')
        ->with('mensaje', 'Equipo actualizado correctamente');
    }


    public function destroy(Equipo $equipo)
    {
        if($equipo->reservaciones->exists()){
            return back()->with('error', 'No se puede eliminar el equipo por que tiene reservaciones');
        }

        $equipo->delete();

        return redirect()->route('equipos.index')

        ->with('mensaje', 'Equipo eliminado correctamente');
    }



    
}

