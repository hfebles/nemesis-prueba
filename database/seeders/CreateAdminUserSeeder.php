<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
  
class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::create([
            'name' => 'Admin', 
            'email' => 'admin@1.com',
            'password' => bcrypt('123')
        ]);

    
        $role = Role::create(['name' => 'Super-Admin']);   
        $permissionsSuperAdmin = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissionsSuperAdmin);    
        $user1->assignRole([$role->id]);

    }
}
