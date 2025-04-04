<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CreateAdmin extends BaseCommand
{
    protected $group       = 'Auth';
    protected $name        = 'auth:create-admin';
    protected $description = 'Crea un usuario administrador para el sistema';

    public function run(array $params)
    {
        // Usar parámetros fijos para simplificar
        $username = 'admin';
        $email    = 'admin@example.com';
        $password = 'admin123456';

        CLI::write("Creando usuario administrador con:");
        CLI::write("Usuario: {$username}");
        CLI::write("Email: {$email}");
        CLI::write("Contraseña: {$password}");

        try {
            // Usar el método register de Shield
            $auth = service('auth');
            $user = $auth->attempt([
                'username' => $username,
                'email'    => $email,
                'password' => $password,
            ], true);

            if (!$user) {
                throw new \Exception('No se pudo crear el usuario');
            }

            // Activar usuario
            $users = model('UserModel');
            $user = $users->findById(auth()->id());
            $user->activate();

            // Asignar al grupo admin
            $authorize = service('authorization');

            // Intentar crear grupo admin si no existe
            if (!$authorize->groupExists('admin')) {
                $authorize->createGroup('admin', 'Administrador con acceso completo');
            }

            $user->addGroup('admin');
            $users->save($user);

            // Cerrar sesión después de crear (opcional)
            $auth->logout();

            CLI::write('Usuario administrador creado con éxito', 'green');
        } catch (\Exception $e) {
            CLI::error('Error al crear usuario: ' . $e->getMessage());
        }
    }
}
