<?php

// Simple script to create admin user directly in the database

// Cargar la aplicación
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
$app = Config\Services::codeigniter();
$app->initialize();

// Configuración
$username = 'admin';
$email = 'admin@example.com';
$password = 'admin123456';

// Hash de la contraseña
$hasher = service('passwords');
$hashedPassword = $hasher->hash($password);

// Conexión a BD
$db = \Config\Database::connect();

try {
    // Iniciar transacción
    $db->transBegin();

    // 1. Insertar usuario
    $db->table('users')->insert([
        'username' => $username,
        'active' => 1,
        'created_at' => date('Y-m-d H:i:s')
    ]);

    $userId = $db->insertID();

    // 2. Insertar identidad (email + contraseña)
    $db->table('auth_identities')->insert([
        'user_id' => $userId,
        'type' => 'email_password',
        'secret' => $email,
        'secret2' => $hashedPassword,
        'created_at' => date('Y-m-d H:i:s')
    ]);

    // 3. Crear grupo admin si no existe
    $adminGroup = $db->table('auth_groups')->where('name', 'admin')->get()->getRow();

    if (!$adminGroup) {
        $db->table('auth_groups')->insert([
            'name' => 'admin',
            'description' => 'Administrador con acceso completo'
        ]);
        $groupId = $db->insertID();
    } else {
        $groupId = $adminGroup->id;
    }

    // 4. Asignar usuario al grupo admin
    $db->table('auth_groups_users')->insert([
        'user_id' => $userId,
        'group' => 'admin'
    ]);

    // Confirmar transacción
    $db->transCommit();

    echo "Usuario administrador creado con éxito\n";
    echo "Usuario: $username\n";
    echo "Email: $email\n";
    echo "Contraseña: $password\n";
} catch (\Exception $e) {
    $db->transRollback();
    echo "Error: " . $e->getMessage() . "\n";
}
