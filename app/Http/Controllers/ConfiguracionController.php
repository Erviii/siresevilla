<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\HistorialRegistrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfiguracionController extends Controller
{
    // Mostrar formulario y listado
    public function index()
    {
        $configuracion = Configuracion::first();
        $registradorActual = HistorialRegistrador::actual()->first();
        $historialRegistradores = HistorialRegistrador::orderBy('fecha_inicio', 'desc')->get();

        return view('configuracion.index', compact('configuracion', 'registradorActual', 'historialRegistradores'));
    }

    // Guardar o actualizar la información del registro
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'nombre_registro' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'periodo_activo' => 'required|string|max:10',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
        ]);

        Configuracion::updateOrCreate(
            ['id' => 1], // Mantiene una sola fila de configuración global
            $request->only(['nombre_registro', 'direccion', 'periodo_activo', 'telefono', 'email'])
        );

        return redirect()->back()->with('success', 'Configuración general actualizada.');
    }

    // Agregar un nuevo registrador y pasar el anterior al historial
    public function cambiarRegistrador(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'titulo_profesional' => 'nullable|string|max:100',
            'accion_designacion' => 'nullable|string|max:150',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Finalizar la gestión del registrador actual
            HistorialRegistrador::where('es_actual', true)->update([
                'es_actual' => false,
                'fecha_fin' => $request->fecha_inicio,
            ]);

            // 2. Registrar al nuevo registrador como actual
            HistorialRegistrador::create([
                'nombre_completo' => $request->nombre_completo,
                'titulo_profesional' => $request->titulo_profesional,
                'fecha_inicio' => $request->fecha_inicio,
                'accion_designacion' => $request->accion_designacion,
                'es_actual' => true,
            ]);
        });

        return redirect()->back()->with('success', 'Nuevo Registrador asignado correctamente.');
    }
}