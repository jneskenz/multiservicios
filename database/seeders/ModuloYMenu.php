<?php

namespace Database\Seeders;

use App\Models\App;
use App\Models\Menu;
use App\Models\Modulo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuloYMenu extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $apps = [
            [
                'nombre' => 'Workspace',
                'descripcion' => 'Módulo de gestión del espacio de trabajo',
                'icono' => 'ti tabler-brand-hipchat',
                'estado' => true,
            ],
            [
                'nombre' => 'Módulo ERP',
                'descripcion' => 'Módulo de gestión Administrativo',
                'icono' => 'ti tabler-key',                
                'estado' => true,
            ]
        ];

        foreach ($apps as  $itemApp) {
            
            App::create($itemApp);

        }

        $modulos = [
            [
                'nombre' => 'Config. Administrativo',
                'descripcion' => '',
                'icono' => 'ti tabler-apps',
                'estado' => true,
                'app_id' => App::where('estado', '1')->first()->id,
            ],
            [
                'nombre' => 'Config. del sistema',
                'descripcion' => '',
                'icono' => 'ti tabler-apps',
                'estado' => true,
                'app_id' => App::where('estado', '1')->first()->id,
            ]
        ];

        foreach ($modulos as $itemModulo){
            Modulo::create($itemModulo);
        }

        $menus = [
            // Menús para el módulo "Config. Administrativo"
            [
                'nombre' => 'Empresas',
                'url' => '/workspace/empresas',
                'icono' => 'ti tabler-building-bank',
                'orden' => 1,
                'estado' => true,
                'modulo_id' => Modulo::where('nombre', 'Config. Administrativo')->first()->id,
            ],
            [
                'nombre' => 'Usuarios',
                'url' => '/workspace/usuarios',
                'icono' => 'ti tabler-users',
                'orden' => 2,
                'estado' => true,
                'modulo_id' => Modulo::where('nombre', 'Config. Administrativo')->first()->id,
            ],
            

        ];

        foreach ($menus as $itemMenu){
            Menu::create($itemMenu);
        }
    }
}
