<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'clientes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'identificacion',
        'tipo_identificacion',
        'nombre',
        'apellido',
        'empresa',
        'direccion',
        'ciudad',
        'telefono',
        'email'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'identificacion' => 'required|min_length[5]|max_length[20]|is_unique[clientes.identificacion,id{id]',
        'tipo_identificacion' => 'required',
        'nombre' => 'required|min_length[3]|max_length[100]',
        'email' => 'permit_empty|valid_email|max_length[100]',
        'telefono' => 'permit_empty|min_length[7]|max_length[20]',
    ];
    protected $validationMessages   = [
        'identificacion' => [
            'required'   => 'El número de identificación es obligatorio',
            'min_length' => 'El número de identificación debe tener al menos {param} caracteres',
            'max_length' => 'El número de identificación no puede exceder {param} caracteres',
            'is_unique'  => 'Este número de identificación ya está registrado',
        ],
        'tipo_identificacion' => [
            'required' => 'El tipo de identificación es obligatorio',
        ],
        'nombre' => [
            'required'   => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos {param} caracteres',
            'max_length' => 'El nombre no puede exceder {param} caracteres',
        ],
        'email' => [
            'valid_email' => 'Por favor, ingrese una dirección de email válida',
            'max_length'  => 'El email no puede exceder {param} caracteres',
        ],
        'telefono' => [
            'min_length' => 'El teléfono debe tener al menos {param} dígitos',
            'max_length' => 'El teléfono no puede exceder {param} dígitos',
        ],
    ];
    protected $skipValidation       = false;
}
