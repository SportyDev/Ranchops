<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return view('configuracion.index', compact('usuarios'));
    }

    public function update(Request $request, User $user)
    {
        // 1. Validamos nombre y correo (asegurando que el correo no lo use alguien más)
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        // 2. Solo validamos y guardamos el rol si viene en el formulario 

        if ($request->has('role')) {
            $rules['role'] = 'required|in:admin,veterinario,vaquero';
            $user->role = $request->role;
        }

        $request->validate($rules);

        // 3. Actualizamos los datos básicos
        $user->name = $request->name;
        $user->email = $request->email;

        // 4. ¿Escribió una contraseña nueva? Si sí, la cambiamos. Si no, se queda la vieja.
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('configuracion.index')->with('exito', 'Datos del usuario actualizados.');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,veterinario,vaquero',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('exito', 'Usuario creado correctamente.');
    }

    public function destroy(User $user)
    {
        // SEGURIDAD: No permitir borrar administradores
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'No se puede eliminar a un administrador.');
        }

        $user->delete();
        return redirect()->back()->with('exito', 'Usuario eliminado.');
    }
}
