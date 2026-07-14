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
            'usuario' => 'required',
            'password' => 'required',
        ]);

        // Buscamos al usuario en la tabla SCTNMUSUA
        $usuario = Usuario::where('usualogin', $request->usuario)->first();

        // ⚠️ VALIDACIÓN DE CONTRASEÑA EN TEXTO PLANO
        // Reemplaza 'USUACLAVE' por la columna real de tu contraseña
        if ($usuario && $usuario->usuapasswr == $request->password) { 
            
            Auth::login($usuario); // Iniciamos sesión
            $request->session()->regenerate();
            
            // Redirigir al sistema principal
            return redirect()->intended('/dashboard'); 
        }

        // Si falla
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
