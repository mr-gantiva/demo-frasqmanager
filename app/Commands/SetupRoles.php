<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Shield\Entities\User;

class SetupRoles extends BaseCommand
{
    protected $group       = 'Auth';
    protected $name        = 'shield:setup-roles';
    protected $description = 'Configura roles y permisos para la aplicación';

    public function run(array $params)
    {
        $auth = service('authorization');

        // Crear roles/grupos
        $auth->createGroup('admin', 'Administrador con acceso completo');
        $auth->createGroup('vendedor', 'Vendedor con acceso limitado');

        // Asignar permisos a grupos
        $auth->addPermissionToGroup('admin.*', 'admin');
        $auth->addPermissionToGroup('productos.*', 'vendedor');
        $auth->addPermissionToGroup('clientes.*', 'vendedor');
        $auth->addPermissionToGroup('ventas.*', 'vendedor');

        // Promover admin existente
        $users = model('UserModel');
        $admin = $users->where('username', 'admin')->first();

        if ($admin) {
            $user = new User($admin);
            $user->addGroup('admin');
            $users->save($user);
            CLI::write('Usuario admin promovido a rol de Administrador', 'green');
        }

        CLI::write('Roles y permisos configurados correctamente', 'green');
    }
}
