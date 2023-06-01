<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
  
class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $permissions = [ 
            'adm-list', 'adm-create', 'adm-edit', 'adm-delete',
            'role-list', 'role-create', 'role-edit', 'role-delete',
            'mantenice-list', 'mantenice-create', 'mantenice-edit', 'mantenice-delete',
            'user-list', 'user-create', 'user-edit', 'user-delete',
            'menu-list', 'menu-create', 'menu-edit', 'menu-delete',
            'sales-list', 'sales-create', 'sales-edit', 'sales-delete',
            'rrhh-list', 'rrhh-create', 'rrhh-edit', 'rrhh-delete',
            'sales-clients-list', 'sales-clients-create', 'sales-clients-edit', 'sales-clients-delete',

            'rrhh-worker-list', 'rrhh-worker-create', 'rrhh-worker-edit', 'rrhh-worker-delete',
            'rrhh-group-worker-list', 'rrhh-group-worker-create', 'rrhh-group-worker-edit', 'rrhh-group-worker-delete',
          
            'user-profile-list', 'user-profile-create', 'user-profile-edit', 'user-profile-delete',

        ];
     
        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}