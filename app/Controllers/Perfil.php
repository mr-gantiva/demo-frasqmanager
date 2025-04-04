<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\UserModel;

class Perfil extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Asegurarse de que el usuario está autenticado
        if (!auth()->loggedIn()) {
            return redirect()->to('login');
        }

        // Obtener email directamente de la tabla auth_identities
        $user = auth()->user();
        $db = db_connect();
        $identity = $db->table('auth_identities')
            ->where('user_id', $user->id)
            ->where('type', 'email_password')
            ->get()
            ->getRowArray();

        $email = $identity['secret'] ?? 'No disponible';

        // Agregar el email al usuario para mostrarlo en la vista
        $user->email = $email;

        $data = [
            'title' => 'Mi Perfil',
            'user' => $user
        ];

        return view('perfil/index', $data);
    }

    public function cambiarPassword()
    {
        // Asegurarse de que el usuario está autenticado
        if (!auth()->loggedIn()) {
            return redirect()->to('login');
        }

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'current_password' => 'required',
                'new_password'     => 'required|min_length[8]|strong_password',
                'confirm_password' => 'required|matches[new_password]',
            ];

            if ($this->validate($rules)) {
                $currentPassword = $this->request->getPost('current_password');
                $newPassword = $this->request->getPost('new_password');

                // Verificar contraseña actual
                $result = auth()->check([
                    'email'    => auth()->user()->email,
                    'password' => $currentPassword,
                ]);

                if (!$result->isOK()) {
                    return redirect()->back()->with('error', 'La contraseña actual es incorrecta.');
                }

                // Cambiar contraseña
                $user = auth()->user();
                $user->fill(['password' => $newPassword]);
                $this->userModel->save($user);

                return redirect()->to('perfil')->with('message', 'Contraseña actualizada exitosamente.');
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $this->validator->getErrors());
            }
        }

        $data = [
            'title' => 'Cambiar Contraseña',
        ];

        return view('perfil/cambiar_password', $data);
    }

    public function actualizar()
    {
        // Asegurarnos de que el usuario está autenticado
        if (!auth()->loggedIn()) {
            return redirect()->to('login');
        }

        if ($this->request->getMethod() === 'post') {
            // Obtener el usuario actual
            $user = auth()->user();
            $userId = $user->id;

            // Obtener el email actual
            $db = db_connect();
            $identity = $db->table('auth_identities')
                ->where('user_id', $userId)
                ->where('type', 'email_password')
                ->get()
                ->getRowArray();

            $currentEmail = $identity['secret'] ?? '';
            $user->email = $currentEmail;

            // AÑADIR AQUÍ LOS PRIMEROS MENSAJES DE LOG (PUNTO 1)
            log_message('debug', 'Actualizando perfil para usuario ID: ' . $userId);
            log_message('debug', 'Username actual: ' . $user->username . ', Nuevo: ' . $this->request->getPost('username'));
            log_message('debug', 'Email actual: ' . $currentEmail . ', Nuevo: ' . $this->request->getPost('email'));

            // Reglas de validación que evitan conflictos con el usuario actual
            $rules = [
                'username' => "required|min_length[3]|is_unique[users.username,id,{$userId}]",
                'email'    => "required|valid_email|is_unique[auth_identities.secret,user_id,{$userId}]",
            ];

            if ($this->validate($rules)) {
                // Usar una transacción para asegurar que todo se actualiza correctamente
                $db->transBegin();

                try {
                    // 1. Actualizar el nombre de usuario
                    $newUsername = $this->request->getPost('username');
                    if ($user->username !== $newUsername) {
                        $db->table('users')
                            ->where('id', $userId)
                            ->update(['username' => $newUsername, 'updated_at' => date('Y-m-d H:i:s')]);

                        // AÑADIR AQUÍ EL LOG DESPUÉS DE ACTUALIZAR USERNAME (PUNTO 2)
                        log_message('debug', 'Actualización de username completada');
                    }

                    // 2. Actualizar el email si ha cambiado
                    $newEmail = $this->request->getPost('email');
                    if ($currentEmail !== $newEmail) {
                        // Actualizar el email en la tabla auth_identities
                        $db->table('auth_identities')
                            ->where('user_id', $userId)
                            ->where('type', 'email_password')
                            ->update(['secret' => $newEmail, 'updated_at' => date('Y-m-d H:i:s')]);

                        // AÑADIR AQUÍ EL LOG DESPUÉS DE ACTUALIZAR EMAIL (PUNTO 2)
                        log_message('debug', 'Actualización de email completada');
                    }

                    $db->transCommit();

                    // AÑADIR AQUÍ UN LOG FINAL DE CONFIRMACIÓN
                    log_message('debug', 'Transacción completada exitosamente');

                    // Recargar el usuario para que refleje los cambios
                    $userModel = new \CodeIgniter\Shield\Models\UserModel();
                    $updatedUser = $userModel->find($userId);
                    auth()->login($updatedUser);

                    return redirect()->to('perfil')->with('message', 'Perfil actualizado exitosamente.');
                } catch (\Exception $e) {
                    $db->transRollback();
                    // AÑADIR AQUÍ UN LOG DE ERROR
                    log_message('error', 'Error al actualizar perfil: ' . $e->getMessage());
                    return redirect()->back()->withInput()->with('error', 'Error al actualizar perfil: ' . $e->getMessage());
                }
            } else {
                // AÑADIR AQUÍ UN LOG DE ERRORES DE VALIDACIÓN
                log_message('debug', 'Errores de validación: ' . print_r($this->validator->getErrors(), true));
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $this->validator->getErrors());
            }
        }

        // Obtener email para mostrar en el formulario
        $db = db_connect();
        $identity = $db->table('auth_identities')
            ->where('user_id', auth()->id())
            ->where('type', 'email_password')
            ->get()
            ->getRowArray();

        $user = auth()->user();
        $user->email = $identity['secret'] ?? '';

        $data = [
            'title' => 'Actualizar Perfil',
            'user' => $user
        ];

        return view('perfil/actualizar', $data);
    }
}
