<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    
    /* * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Apaga o cache de permissões
        // app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // //Cria permissões:
        // Permission::create(['name' => 'create']);
        // Permission::create(['name' => 'edit']);
        // Permission::create(['name' => 'delete']);
        // Permission::create(['name' => 'view']);

        //Criar papel de adm
        // $admin = Role::create(['name' => 'admin']);
        // $admin->givePermissionTo(Permission::all());

        // $colabAlfa = Role::create(['name' => 'viewer']);
        // $colabAlfa->givePermissionTo(['view']);
    }
}
