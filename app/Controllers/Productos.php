<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductoModel;

class Productos extends BaseController
{
    protected $productoModel;
    protected $validation;

    public function __construct()
    {
        $this->productoModel = new ProductoModel();
        $this->validation = \Config\Services::validation();
    }
    public function index()
    {
        $data = [
            'title' => 'Gestión de Productos',
            'productos' => $this->productoModel->findAll(),
        ];
        return view('productos/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Crear Nuevo Producto',
        ];
        return view('productos/new', $data);
    }

    public function create()
    {
        // Validar los datos del formulario
        if (!$this->validate($this->productoModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
        }

        // Guardar el producto en la base de datos
        $this->productoModel->save([
            'codigo' => $this->request->getPost('codigo'),
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio_compra' => $this->request->getPost('precio_compra'),
            'precio_venta' => $this->request->getPost('precio_venta'),
            'stock' => $this->request->getPost('stock'),
            'categoria' => $this->request->getPost('categoria'),
        ]);

        // Redirigir a la lista de productos con un mensaje de éxito
        return redirect()->to('/productos')->with('message', 'Producto creado exitosamente.');
    }

    public function edit($id = null)
    {
        $producto = $this->productoModel->find($id);
        if ($producto) {
            $data = [
                'title' => 'Editar Producto',
                'producto' => $producto,
            ];
            return view('productos/edit', $data);
        } else {
            return redirect()->to('/productos')->with('error', 'Producto no encontrado.');
        }
    }

    public function update($id = null)
    {

        $producto = $this->productoModel->find($id);
        if (!$producto) {
            return redirect()->to('/productos')->with('error', 'Producto no encontrado.');
        }

        // Validar los datos del formulario excluyendo el campo código
        $validationRules = [
            'nombre' => 'required|min_length[3]|max_length[255]',
            'precio_compra' => 'required|numeric',
            'precio_venta' => 'required|numeric',
            'stock' => 'required|integer',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
        }

        // Actualizar producto en la base de datos
        $this->productoModel->update($id, [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio_compra' => $this->request->getPost('precio_compra'),
            'precio_venta' => $this->request->getPost('precio_venta'),
            'stock' => $this->request->getPost('stock'),
            'categoria' => $this->request->getPost('categoria'),
        ]);
        // Redirigir a la lista de productos con un mensaje de éxito
        return redirect()->to('/productos')->with('message', 'Producto actualizado exitosamente.');
    }

    public function delete($id = null)
    {
        // Eliminar producto
        $this->productoModel->delete($id);
        // Redirigir a la lista de productos con un mensaje de éxito
        return redirect()->to('/productos')->with('message', 'Producto eliminado exitosamente.');
    }
}
