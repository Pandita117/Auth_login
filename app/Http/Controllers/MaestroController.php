<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MaestroController extends Controller
{

//lista los destos
public function index()
    {
        $alumnos = Alumno::with('user')->orderBy('id', 'asc')->get();
        return view('alumnos.index', compact('alumnos'));
    }

//formulario para crear nuevo chaval
    public function create()
    {
        return view('alumnos.create');
    }

    //guarda el user con rol de alu
    public function store(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
            
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $request->password,
            ]);

            $user->assignRole('alumno');

            Alumno::create([
                'user_id'      => $user->id,
                'calificacion' => 0,
                'faltas'       => 0,
            ]);
        });

        return redirect()->route('alumnos.index')
            ->with('status', 'Alumno registrado correctamente.');
    }

//con este editas
    public function edit(Alumno $alumno)
    {
        $alumno->load('user');
        return view('alumnos.edit', compact('alumno'));
    }

    // con este actualizas
    public function update(Request $request, Alumno $alumno)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($alumno->user_id)],
            'password'     => ['nullable', 'string', 'min:8', 'confirmed'],
            'calificacion' => ['required', 'numeric', 'min:0', 'max:100'],
            'faltas'       => ['required', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($request, $alumno) {
            $datosUser = [
                'name'  => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $datosUser['password'] = $request->password;
            }

            $alumno->user->update($datosUser);

            $alumno->update([
                'calificacion' => $request->calificacion,
                'faltas'       => $request->faltas,
            ]);
        });

        return redirect()->route('alumnos.index')
            ->with('status', 'Calificación y datos del alumno actualizados.');
    }

//bborra
    public function destroy(Alumno $alumno)
    {
        $alumno->user->delete();

        return redirect()->route('alumnos.index')
            ->with('status', 'Alumno eliminado correctamente.');
    }
}