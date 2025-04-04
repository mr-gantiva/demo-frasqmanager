<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'productos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['codigo', 'nombre', 'descripcion', 'precio_compra', 'precio_venta', 'stock', 'categoria'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules    = [
        'codigo' => 'required|min_length[3]|max_length[50]|is_unique[productos.codigo,id,{id}]',
        'nombre' => 'required|min_length[3]|max_length[255]',
        'precio_compra' => 'required|numeric',
        'precio_venta' => 'required|numeric',
        'stock' => 'required|integer',
    ];
    protected $validationMessages   = [
        'codigo' => [
            'required'   => 'El código del producto es obligatorio',
            'min_length' => 'El código debe tener al menos {param} caracteres',
            'max_length' => 'El código no puede exceder {param} caracteres',
            'is_unique'  => 'Este código ya está en uso. Por favor, ingrese un código único',
        ],
        'nombre' => [
            'required'   => 'El nombre del producto es obligatorio',
            'min_length' => 'El nombre debe tener al menos {param} caracteres',
            'max_length' => 'El nombre no puede exceder {param} caracteres',
        ],
        'precio_compra' => [
            'required' => 'El precio de compra es obligatorio',
            'numeric'  => 'El precio de compra debe ser un valor numérico',
        ],
        'precio_venta' => [
            'required' => 'El precio de venta es obligatorio',
            'numeric'  => 'El precio de venta debe ser un valor numérico',
        ],
        'stock' => [
            'required' => 'El stock es obligatorio',
            'integer'  => 'El stock debe ser un número entero',
        ],
    ];
    protected $skipValidation       = false;
}
