<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            // ═══════════════════════════════════════════════════════════
            // FASE 1: PERMISOS Y ROLES (deben ejecutarse primero)
            // ═══════════════════════════════════════════════════════════
            // RolePermissionSeeder::class,        // Roles y permisos base
            SuperAdminSeeder::class,            // Superadmin
            RolesYPermisosSeeder::class,        // Roles adicionales
            
            // ═══════════════════════════════════════════════════════════
            // FASE 2: ESTRUCTURA ORGANIZACIONAL
            // ═══════════════════════════════════════════════════════════
            GrupoEmpresaSeeder::class,          // 3 grupos empresariales
            EmpresaMultiempresaSeeder::class,   // 7 empresas
            SedeMultiempresaSeeder::class,      // 6 sedes
            LocalMultiempresaSeeder::class,     // 17 locales + relaciones empresa-local            // ═══════════════════════════════════════════════════════════
            // FASE 3: USUARIOS Y SUPERADMIN
            // ═══════════════════════════════════════════════════════════
            
            UsuarioMultiempresaSeeder::class,   // 13 usuarios con diferentes roles
            
        ]);
        
    }
}
