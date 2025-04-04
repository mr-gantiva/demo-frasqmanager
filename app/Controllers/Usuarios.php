<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class Usuarios extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Verificar permiso
        if (!auth()->user()->can('admin.users')) {
            return redirect()->to('/')->with('error', 'No tiene permisos para acceder a esta sección');
        }

        $data = [
            'title' => 'Gestión de Usuarios',
            'usuarios' => $this->userModel->findAll()
        ];

        return view('usuarios/index', $data);
    }

    public function new()
    {
        if (!auth()->user()->can('admin.users')) {
            return redirect()->to('/')->with('error', 'No tiene permisos para acceder a esta sección');
        }

        $data = [
            'title' => 'Crear Nuevo Usuario',
            'grupos' => ['admin' => 'Administrador', 'vendedor' => 'Vendedor']
        ];

        return view('usuarios/new', $data);
    }

    public function create()
    {
        if (!auth()->user()->can('admin.users')) {
            return redirect()->to('/')->with('error', 'No tiene permisos para acceder a esta sección');
        }

        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[auth_identities.secret]',
            'password' => 'required|strong_password',
            'grupo'    => 'required|in_list[admin,vendedor]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Usar inserción directa en la base de datos
        $db = db_connect();
        $db->transBegin();

        try {
            // 1. Insertar usuario
            $db->table('users')->insert([
                'username' => $this->request->getPost('username'),
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $userId = $db->insertID();

            // 2. Insertar identidad (email + contraseña)
            $db->table('auth_identities')->insert([
                'user_id' => $userId,
                'type' => 'email_password',
                'secret' => $this->request->getPost('email'),
                'secret2' => service('passwords')->hash($this->request->getPost('password')),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // 3. Asignar al grupo
            $db->table('auth_groups_users')->insert([
                'user_id' => $userId,
                'group' => $this->request->getPost('grupo'),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $db->transCommit();

            return redirect()->to('usuarios')->with('message', 'Usuario creado exitosamente');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    public function delete($id = null)
    {
        if (!auth()->user()->can('admin.users')) {
            return redirect()->to('/')->with('error', 'No tiene permisos para acceder a esta sección');
        }

        // No permitir eliminar el propio usuario
        if ($id == auth()->id()) {
            return redirect()->to('usuarios')->with('error', 'No puede eliminar su propio usuario');
        }

        $this->userModel->delete($id);

        return redirect()->to('usuarios')->with('message', 'Usuario eliminado exitosamente');
    }
}
