<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Apaga o cache de permissões
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //Cria permissões:
        Permission::create(['name' => 'criar usuário']);
        Permission::create(['name' => 'editar usuário']);
        Permission::create(['name' => 'excluir usuário']);
        Permission::create(['name' => 'visualizar usuário']);
        Permission::create(['name' => 'requisitar veículo']);

        //Criar papel de adm
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $colabAlfa = Role::create(['name' => 'colaborador alfa']);
        $colabAlfa->givePermissionTo(['requisitar veículo']);
    }
}
