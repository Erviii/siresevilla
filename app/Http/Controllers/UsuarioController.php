<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    /**
     * Listado de usuarios
     */
  public function index()
{
    $usuarios = Usuario::with('roles')->get();
    $roles = \Spatie\Permission\Models\Role::all(); // <-- Añadir para alimentar el modal

    return view('usuarios.index', compact('usuarios', 'roles'));
}

    /**
     * Formulario para crear un usuario nuevo
     */
    public function create()
    {
        $roles = Role::all();
        return view('usuarios.create', compact('roles'));
    }

    /**
     * Guardar el usuario nuevo
     */
    public function store(Request $request)
    {
        $request->validate([
            'usualogin' => 'required|string|max:50|unique:sctnmusua,usualogin',
            'usuanombr' => 'required|string|max:100',
            'usuapasswr' => 'required|string|min:6',
            'role'      => 'required',
        ]);

        
        DB::transaction(function () use ($request) {

// 1. Obtener el valor máximo actual de usuacodusu y convertirlo a entero (soporta si viene como tipo texto/char)
        $maxCodigo = Usuario::max('usuacodusu');
        
        // 2. Si no hay registros previos, inicia en 1, de lo contrario suma +1
        $nuevoCodigo = ($maxCodigo ? (int) $maxCodigo : 0) + 1;


            // 1. Crear el registro en sctnmusua
            $usuario = Usuario::create([
                 'usuacodusu' => $nuevoCodigo,
                'usualogin' => trim($request->usualogin),
                'usuanombre' => trim($request->usuanombr),
                'usuapasswr' =>trim($request->usuapasswr), // Clave encriptada con Bcrypt
                'usuastatus' => 'AC', // Activo por defecto
                'usuatitulo'  => $request->usuacargo ?? 'FUNCIONARIO REGISTRAL',
                'usuafecing' => now(),
            ]);

            // 2. Asignar el Rol de Spatie
            $usuario->assignRole($request->role);
        });

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente con su rol correspondiente.');
    }

    /**
     * Formulario de edición
     */
    public function edit($login)
    {
        $usuario = Usuario::findOrFail($login);
        $roles = Role::all();
        $userRole = $usuario->roles->first()->name ?? null;

        return view('usuarios.edit', compact('usuario', 'roles', 'userRole'));
    }

    /**
     * Actualizar usuario y su rol
     */
    public function update(Request $request, $login)
    {
        $usuario = Usuario::findOrFail($login);

        $request->validate([
            'usuanombr' => 'required|string|max:100',
            'role'      => 'required',
        ]);

        DB::transaction(function () use ($request, $usuario) {
            $data = [
                'usuanombre' => $request->filled('usuanombr') ? trim($request->usuanombr) : $usuario->usuanombre,
                'usuastatus' => $request->usuastatus ?? 'AC',
                'usuatitulo'  => $request->usuacargo,
            ];

            // Si el usuario escribió una contraseña nueva, la actualizamos
            if ($request->filled('usuapasswr')) {
                $data['usuapasswr'] = Hash::make($request->usuapasswr);
            }

            $usuario->update($data);

            // Sincronizar el nuevo Rol (reemplaza el anterior)
            $usuario->syncRoles([$request->role]);
        });

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario y nivel de acceso actualizados correctamente.');
    }
}