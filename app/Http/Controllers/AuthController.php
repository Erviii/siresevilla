<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class AuthController extends Controller
{
    // Mostrar el formulario de Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesar el Login
    public function login(Request $request)
    {
        $request->validate([
            'usuario'  => 'required|string',
            'password' => 'required|string',
        ]);

        $login = trim($request->usuario);

        // Buscamos al usuario en la tabla SCTNMUSUA por usualogin
        $usuario = Usuario::where('usualogin', $login)->first();

        // 1. Verificamos credenciales (Comparación de contraseña)
        if ($usuario && trim($usuario->usuapasswr) === $request->password) {
            
            // 2. Verificamos que el usuario esté ACTIVO ('AC')
            if (trim($usuario->usuastatus) !== 'AC') {
                return back()->withErrors([
                    'usuario' => 'Su cuenta se encuentra inactiva o suspendida. Contacte al administrador.',
                ])->onlyInput('usuario');
            }

            // 3. Iniciamos sesión y regeneramos la sesión de Laravel
            Auth::login($usuario);
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        // Si falla la autenticación
        return back()->withErrors([
            'usuario' => 'Las credenciales ingresadas son incorrectas.',
        ])->onlyInput('usuario');
    }

    // Cerrar Sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}