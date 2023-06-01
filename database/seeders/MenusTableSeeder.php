<?php

namespace Database\Seeders;

use App\Models\Conf\Menu;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::create(['id' => 1, 'name' => 'Clientes', 'slug' => 'clients.index', 'grupo' => 'sales', 'icono' => 'fa-solid fa-file-invoice', 'parent' => '0', 'order' => '0', 'href' => '0',]);
        Menu::create(['id' => 6, 'name' => 'RRHH', 'slug' => '/', 'grupo' => 'rrhh', 'icono' => 'fa-sharp fa-solid fa-address-card', 'parent' => '0', 'order' => '7', 'href' => '1', ]);
        Menu::create(['id' => 7, 'name' => 'Configuraciones','slug' => '/','grupo' => 'mantenice','icono' => 'fa-solid fa-screwdriver-wrench','parent' => '0','order' => '10','href' => '0',]);


      
        //RRHH
        Menu::create(['id' => 23, 'name' => 'Trabajadores', 'slug' => 'workers.index', 'grupo' => 'rrhh-worker', 'icono' => 'fa-solid fa-user-tie', 'parent' => '6', 'order' => '0', 'href' => '0', ]);
        Menu::create(['id' => 24, 'name' => 'Grupos de trabajo', 'slug' => 'group-workers.index', 'grupo' => 'rrhh-group-worker', 'icono' => 'fa-solid fa-clipboard-user', 'parent' => '6', 'order' => '1', 'href' => '0', ]);


        //CONFIGURACIONES
        Menu::create(['id' => 28, 'name' => 'Menús','slug' => 'menu.index','grupo' => 'menu','icono' => 'fa-sharp fa-solid fa-table-list','parent' => '7','order' => '3','href' => '0',]);
        Menu::create(['id' => 30, 'name' => 'Permisologia','slug' => 'roles.index','grupo' => 'roles','icono' => 'fa-solid fa-users-rectangle','parent' => '7','order' => '4','href' => '0',]);
        Menu::create(['id' => 32, 'name' => 'Usuarios','slug' => 'users.index','grupo' => 'user','icono' => 'fa fa-address-card','parent' => '7','order' => '7','href' => '2',]);
        Menu::create(['id' => 33, 'name' => 'Compañias','slug' => 'compania.index','grupo' => 'adm','icono' => 'fa fa-address-card','parent' => '7','order' => '7','href' => '1',]);

        }

}
