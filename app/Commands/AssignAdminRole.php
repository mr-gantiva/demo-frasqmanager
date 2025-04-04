<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class AssignAdminRole extends BaseCommand
{
    protected $group       = 'Auth';
    protected $name        = 'auth:assign-admin';
    protected $description = 'Asigna el rol de administrador a usuarios específicos';

    public function run(array $params)
    {
        // Obtener el servicio de autorización
        $authorize = service('authorization');

        // Crear el grupo admin si no existe
        if (!$authorize->groupExists('admin')) {
            CLI::write("Creando grupo 'admin'...", 'yellow');
            $authorize->createGroup('admin', 'Administrador con acceso completo');
            CLI::write("Grupo 'admin' creado exitosamente.", 'green');
        } else {
            CLI::write("El grupo 'admin' ya existe.", 'yellow');
        }

        // Obtener todos los usuarios
        $userModel = new \CodeIgniter\Shield\Models\UserModel();
        $users = $userModel->findAll();

        CLI::write("Usuarios en el sistema:", 'cyan');
        foreach ($users as $user) {
            CLI::write("ID: {$user->id}, Usuario: {$user->username}, Email: {$user->email}", 'white');

            // Verificar grupos del usuario
            $groups = $user->getGroups();
            CLI::write("  Grupos: " . (empty($groups) ? "Ninguno" : implode(', ', $groups)), 'white');

            // Para usuarios con ciertos nombres
            if ($user->username === 'admin' || $user->username === 'ivan') {
                CLI::write("  Asignando grupo 'admin' al usuario {$user->username}...", 'yellow');
                $user->addGroup('admin');
                $userModel->save($user);
                CLI::write("  Grupo asignado exitosamente.", 'green');
            }
        }

        CLI::write("\nProceso completado. Los usuarios deben tener sus roles correctamente asignados.", 'green');
    }
}
