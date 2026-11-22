<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::with('roles')->orderBy('id')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::pluck('name');
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'rol'      => 'required|in:admin,jugador',
        ]);

        $usuario = User::create([
            'name'     => $datos['name'],
            'email'    => $datos['email'],
            'password' => Hash::make($datos['password']),
        ]);

        $usuario->assignRole($datos['rol']);

        return redirect()->route('usuarios.index')->with('mensaje', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        $roles = Role::pluck('name');
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, User $usuario)
    {
        $datos = $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:6',
            'rol'      => 'required|in:admin,jugador',
        ]);

        $usuario->update([
            'name'  => $datos['name'],
            'email' => $datos['email'],
        ]);

        if (!empty($datos['password'])) {
            $usuario->update(['password' => Hash::make($datos['password'])]);
        }

        $usuario->syncRoles([$datos['rol']]);

        return redirect()->route('usuarios.index')->with('mensaje', 'Usuario actualizado.');
    }

    public function destroy(User $usuario)
    {
        if ($usuario->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $usuario->delete();
        return redirect()->route('usuarios.index')->with('mensaje', 'Usuario eliminado.');
    }
}