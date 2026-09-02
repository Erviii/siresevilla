<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Usuario;

class RolesYPermisosSeeder extends Seeder
{
    public function run()
    {
        // Resetear caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. CREAR PERMISOS DEL SIRE
        $permisos = [
            // Fichas Registrales
            'ver-fichas',
            'crear-fichas',
            'editar-fichas',

            // Movimientos e Inscripciones
            'ver-movimientos',
            'crear-movimientos',
            'editar-movimientos',

            // Reportes e Impresiones
            'imprimir-razon',
            'imprimir-acta',
            'imprimir-ficha',

            // Configuración y Usuarios
            'administrar-usuarios',
            'configurar-sistema',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // 2. CREAR ROLES Y ASIGNAR PERMISOS
        
        // Rol Super Admin
        $rolAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $rolAdmin->syncPermissions(Permission::all());

        // Rol Registrador
        $rolRegistrador = Role::firstOrCreate(['name' => 'Registrador']);
        $rolRegistrador->syncPermissions([
            'ver-fichas', 'crear-fichas', 'editar-fichas',
            'ver-movimientos', 'crear-movimientos', 'editar-movimientos',
            'imprimir-razon', 'imprimir-acta', 'imprimir-ficha'
        ]);

        // Rol Digitador
        $rolDigitador = Role::firstOrCreate(['name' => 'Digitador']);
        $rolDigitador->syncPermissions([
            'ver-fichas', 'crear-fichas',
            'ver-movimientos', 'crear-movimientos',
            'imprimir-razon', 'imprimir-acta'
        ]);

        // 3. ASIGNAR ROL AL PRIMER USUARIO ADMINISTRADOR
        $admin = Usuario::first(); // O busca por tu código de usuario admin
        if ($admin) {
            $admin->assignRole('Super Admin');
        }
    }
}