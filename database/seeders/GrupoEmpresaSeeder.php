<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GrupoEmpresa;
use Illuminate\Support\Str;

class GrupoEmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $grupos = [
            [
                'user_uuid' => \Illuminate\Support\Str::uuid(),
                'nombre' => 'Grupo Corporativo ABC',
                'codigo' => 'GRP-001',
                'slug' => 'abc',
                'ruc' => '20123456789',
                'descripcion' => 'Grupo empresarial líder en el sector retail y servicios - ABC HOLDING S.A.C.',
                'email' => 'contacto@grupoabc.com',
                'telefono' => '01-2345670',
                'estado' => '1',
                'plan_id' => 'empresarial',
                'max_empresas' => 10,
                'max_usuarios' => 100,
            ],
            [
                'user_uuid' => \Illuminate\Support\Str::uuid(),
                'nombre' => 'Grupo Comercial XYZ',
                'codigo' => 'GRP-002',
                'slug' => 'xyz',
                'ruc' => '20987654321',
                'descripcion' => 'Corporación especializada en tecnología y consultoría - XYZ CORPORACIÓN S.A.C.',
                'email' => 'contacto@grupoxyz.com',
                'telefono' => '01-3456780',
                'estado' => '1',
                'plan_id' => 'profesional',
                'max_empresas' => 5,
                'max_usuarios' => 50,
            ],
            [
                'user_uuid' => \Illuminate\Support\Str::uuid(),
                'nombre' => 'Grupo Industrial DEF',
                'codigo' => 'GRP-003',
                'slug' => 'def',
                'ruc' => '20456789123',
                'descripcion' => 'Grupo industrial con presencia nacional - DEF INDUSTRIES S.A.',
                'email' => 'contacto@grupodef.com',
                'telefono' => '01-4567880',
                'estado' => '1',
                'plan_id' => 'profesional',
                'max_empresas' => 5,
                'max_usuarios' => 50,
            ],
        ];

        foreach ($grupos as $grupoData) {
            GrupoEmpresa::create($grupoData);
            
            $this->command->info("✓ Grupo creado: {$grupoData['nombre']}");
        }

        $this->command->info('');
        $this->command->info("Total: " . count($grupos) . " grupos empresariales creados");
    }
}
