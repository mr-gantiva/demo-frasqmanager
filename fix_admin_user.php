<?php
// Cargar la aplicación
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
$app = Config\Services::codeigniter();
$app->initialize();

// Obtener el servicio de autorización
$authorize = service('authorization');

// Crear el grupo admin si no existe
if (!$authorize->groupExists('admin')) {
    echo "Creando grupo 'admin'...\n";
    $authorize->createGroup('admin', 'Administrador con acceso completo');
    echo "Grupo 'admin' creado exitosamente.\n";
} else {
    echo "El grupo 'admin' ya existe.\n";
}

// Obtener todos los usuarios
$userModel = new \CodeIgniter\Shield\Models\UserModel();
$users = $userModel->findAll();

echo "Usuarios en el sistema:\n";
foreach ($users as $user) {
    echo "ID: {$user->id}, Usuario: {$user->username}, Email: {$user->email}\n";

    // Verificar grupos del usuario
    $groups = $user->getGroups();
    echo "  Grupos: " . (empty($groups) ? "Ninguno" : implode(', ', $groups)) . "\n";

    // Para el usuario que es administrador (ajusta según tu usuario)
    if ($user->username === 'ivan' || $user->username === 'admin') {
        echo "  Asignando grupo 'admin' al usuario {$user->username}...\n";
        $user->addGroup('admin');
        $userModel->save($user);
        echo "  Grupo asignado exitosamente.\n";
    }
}

echo "\nProceso completado. Ahora los usuarios deben tener sus roles correctamente asignados.\n";
