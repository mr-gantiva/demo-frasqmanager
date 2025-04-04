<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClienteModel;

class Clientes extends BaseController
{
    protected $clienteModel;
    protected $validation;

    public function __construct()
    {
        $this->clienteModel = new ClienteModel();
        $this->validation = \Config\Services::validation();
    }
    public function index()
    {
        $data = [
            'title' => 'Clientes',
            'clientes' => $this->clienteModel->findAll(),
        ];
        return view('clientes/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Registrar Nuevo Cliente',
            'tipos_identificacion' => [
                'CC' => 'Cédula de Ciudadanía',
                'NIT' => 'Número de Identificación Tributaria',
                'CE' => 'Cédula de Extranjería',
                'TI' => 'Tarjeta de Identidad',
                'PP' => 'Pasaporte',
                'OTRO' => 'Otro'
            ]
        ];
        return view('clientes/new', $data);
    }

    public function create()
    {
        // Validar los datos del formulario
        if (!$this->validate($this->clienteModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        // Guardar el cliente en la base de datos
        $this->clienteModel->save([
            'identificacion' => $this->request->getPost('identificacion'),
            'tipo_identificacion' => $this->request->getPost('tipo_identificacion'),
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'empresa' => $this->request->getPost('empresa'),
            'direccion' => $this->request->getPost('direccion'),
            'ciudad' => $this->request->getPost('ciudad'),
            'telefono' => $this->request->getPost('telefono'),
            'email' => $this->request->getPost('email'),
        ]);
        // Redirigir a la lista de clientes con un mensaje de éxito
        return redirect()->to('/clientes')->with('success', 'Cliente registrado exitosamente.');
    }

    public function edit($id = null)
    {
        $cliente = $this->clienteModel->find($id);

        if ($cliente) {
            $data = [
                'title' => 'Editar Cliente',
                'cliente' => $cliente,
                'tipos_identificacion' => [
                    'CC' => 'Cédula de Ciudadanía',
                    'NIT' => 'Número de Identificación Tributaria',
                    'CE' => 'Cédula de Extranjería',
                    'TI' => 'Tarjeta de Identidad',
                    'PP' => 'Pasaporte',
                    'OTRO' => 'Otro'
                ]
            ];
            return view('clientes/edit', $data);
        } else {
            return redirect()->to('/clientes')->with('error', 'Cliente no encontrado.');
        }
    }

    public function update($id = null)
    {
        // Verificar si el cliente existe
        $cliente = $this->clienteModel->find($id);
        if (!$cliente) {
            return redirect()->to('/clientes')->with('error', 'Cliente no encontrado.');
        }
        // Validar los datos del formulario
        $validationRules = [
            'tipo_identificacion' => 'required',
            'nombre' => 'required|min_length[3]|max_length[100]',
            'email' => 'permit_empty|valid_email|max_length[100]',
            'telefono' => 'permit_empty|regex_match[/^[0-9]{10}$/]',
        ];

        if ($this->request->getPost('identificacion') !== $cliente['identificacion']) {
            $validationRules['identificacion'] = 'required|min_length[5]|max_length[20]|is_unique[clientes.identificacion]';
        }

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
        }


        // Actualizar el cliente en la base de datos
        $this->clienteModel->update($id, [
            'identificacion' => $this->request->getPost('identificacion'),
            'tipo_identificacion' => $this->request->getPost('tipo_identificacion'),
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'empresa' => $this->request->getPost('empresa'),
            'direccion' => $this->request->getPost('direccion'),
            'ciudad' => $this->request->getPost('ciudad'),
            'telefono' => $this->request->getPost('telefono'),
            'email' => $this->request->getPost('email'),
        ]);
        // Redirigir a la lista de clientes con un mensaje de éxito
        return redirect()->to('/clientes')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function delete($id = null)
    {
        // Verificar si el cliente existe
        $cliente = $this->clienteModel->find($id);
        if (!$cliente) {
            return redirect()->to('/clientes')->with('error', 'Cliente no encontrado.');
        }
        // Eliminar el cliente de la base de datos
        $this->clienteModel->delete($id);
        // Redirigir a la lista de clientes con un mensaje de éxito
        return redirect()->to('/clientes')->with('success', 'Cliente eliminado exitosamente.');
    }


    public function view($id = null)
    {
        $cliente = $this->clienteModel->find($id);

        if ($cliente) {
            $data = [
                'title' => 'Detalles del Cliente',
                'cliente' => $cliente,
            ];
            return view('clientes/view', $data);
        } else {
            return redirect()->to('/clientes')->with('error', 'Cliente no encontrado.');
        }
    }
}
