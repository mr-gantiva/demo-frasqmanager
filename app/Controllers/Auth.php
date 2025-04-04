<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Controllers\LoginController;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{
    public function login()
    {
        // Verificar si ya está autenticado
        if (auth()->loggedIn()) {
            return redirect()->to('/');
        }

        return view('auth/login');
    }

    public function attemptLogin()
    {
        // Validar datos del formulario
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Intentar autenticación
        $credentials = [
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ];

        $loginAttempt = auth()->attempt($credentials);

        if (! $loginAttempt->isOK()) {
            return redirect()->back()->withInput()->with('error', $loginAttempt->reason());
        }

        // Autenticación exitosa
        return redirect()->to('/')->with('message', 'Bienvenido/a de nuevo');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->to('login')->with('message', 'Sesión cerrada correctamente');
    }
}
