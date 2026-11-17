<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    //funcion para mostar
    public function index()
    {
        //mostar los usuarios con por id ascendente
        $usuarios = User::with('roles')->orderBy('id', 'asc')->get();

        $usuariosEliminados = Auth::user()->can('users.restore')
        ? User::onlyTrashed()->with('roles')->orderBy('deleted_at', 'desc')->get()
        : collect();

        //le mandamos la vista

        return view('users.index', compact('usuarios', 'usuariosEliminados'));
        
    }

    /**
     * Show the form for creating a new resource.
     */

    //crear usuarios
    public function create()
    {
        //aqui decimos que cualquiera de los dos puede crear usuarios
        $roles = Role::all();
        return view('users.create', compact('roles'));

        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validaciones para guardar registros
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required' , 'string', 'email' , 'max:255' , 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'exists:roles,name'],

            

        ]);

            //para que usario que no es admin no pueda crear usuarios admin
        if(!Auth::user()->isAdmin() && $request->role == 'admin') {
            abort(403, 'No tienes permisos para asignar el rol de Administrador.');
        }

        //aqui se crera el usuario 
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $user->assignRole($request->role);

        //lo redirigriemos a donde se listan los usuarios
        return redirect()->route('users.index')
            ->with('status', 'Usuario registrado correctamente');

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles');
        return view('users.show', compact('user'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //todos los usuarios pueden editar
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, User $user)
{
    $esAdmin = Auth::user()->hasRole('admin');

    if (!$esAdmin && $user->hasRole('admin')) {
        abort(403, 'No tienes permisos para editar usuarios con rol de Administrador.');
    }

    $request->validate([
        'name'     => ['required', 'string', 'max:255'],
        'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        'role'     => $esAdmin ? ['required', 'exists:roles,name'] : ['nullable'],
    ]);

    $datos = [
        'name'  => $request->name,
        'email' => $request->email,
    ];

    if ($request->filled('password')) {
        $datos['password'] = $request->password;
    }

    $user->update($datos);

    if ($esAdmin) {
        $user->syncRoles([$request->role]);
    } else {
        $user->syncRoles(['Empleado']);
    }

    return redirect()->route('users.index')
        ->with('status', 'Usuario actualizado correctamente');
}

    /**
     * Remove the specified resource from storage.
     */

    //el destroy normal es el el SoftDelete
public function destroy(User $user)
    {

        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta activa.');
        }

        $user->delete();
        return redirect()->route('users.index')
            ->with('status', 'Usuario deshabilitado (Soft Delete) correctamente.');
    }

    // Restaurar registro eliminado lógicamente 
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('users.index')
            ->with('status', 'Usuario restaurado correctamente.');
    }

    // Eliminación Permanente (
    public function forceDelete($id)
    {

        //Para que no se elimine el mismo #Di no al suicidio
        if ((int) $id === Auth::id()) {
            return back()->with('error', 'No puedes eliminar permanentemente tu propia cuenta.');
        }

        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();

        return redirect()->route('users.index')
            ->with('status', 'Usuario eliminado permanentemente de la base de datos.');
    }

}
