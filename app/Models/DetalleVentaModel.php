<?php

namespace App\Models;

use CodeIgniter\Model;

class DetalleVentaModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'detalles_venta';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'venta_id'        => 'required|integer',
        'producto_id'     => 'required|integer',
        'cantidad'        => 'required|integer|greater_than[0]',
        'precio_unitario' => 'required|numeric',
        'subtotal'        => 'required|numeric',
    ];
    protected $validationMessages   = [
        'venta_id' => [
            'required' => 'El ID de venta es obligatorio',
            'integer'  => 'El ID de venta debe ser un número entero',
        ],
        'producto_id' => [
            'required' => 'Debe seleccionar un producto',
            'integer'  => 'El producto seleccionado no es válido',
        ],
        'cantidad' => [
            'required'      => 'La cantidad es obligatoria',
            'integer'       => 'La cantidad debe ser un número entero',
            'greater_than'  => 'La cantidad debe ser mayor que cero',
        ],
        'precio_unitario' => [
            'required' => 'El precio unitario es obligatorio',
            'numeric'  => 'El precio unitario debe ser un valor numérico',
        ],
        'subtotal' => [
            'required' => 'El subtotal es obligatorio',
            'numeric'  => 'El subtotal debe ser un valor numérico',
        ],
    ];
    protected $skipValidation       = false;

    // Relaciones
    public function venta()
    {
        return $this->belongsTo('VentaModel::class', 'venta_id');
    }
    public function producto()
    {
        return $this->belongsTo('ProductoModel::class', 'producto_id');
    }
}
