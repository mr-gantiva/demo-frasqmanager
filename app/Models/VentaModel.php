<?php

namespace App\Models;

use CodeIgniter\Model;

class VentaModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'ventas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'codigo',
        'cliente_id',
        'fecha',
        'total',
        'estado',
        'canal_venta_id',
        'notas',
        'usuario_id',
        'estado_venta',
        'comision',
        'comision_fija',
        'valor_envio',
        'tipo_envio',
        'tipo_publicacion',
        'reteiva',
        'wompi_comision',
        'wompi_iva',
        'factura_electronica',
        'estado_pago',
        'banco',
        'fecha_actualizacion_banco',
        'valor_real'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'codigo'     => 'required|min_length[5]|max_length[20]|is_unique[ventas.codigo,id,{id}]',
        'cliente_id' => 'required|integer',
        'fecha'      => 'required|valid_date',
        'total'      => 'required|numeric',
        'estado'     => 'required|in_list[completada,anulada,pendiente]',
        'canal_venta' => 'permit_empty|max_length[50]',
    ];
    protected $validationMessages   = [
        'codigo' => [
            'required'   => 'El código de venta es obligatorio',
            'min_length' => 'El código debe tener al menos {param} caracteres',
            'max_length' => 'El código no puede exceder {param} caracteres',
            'is_unique'  => 'Este código de venta ya está registrado',
        ],
        'cliente_id' => [
            'required' => 'Debe seleccionar un cliente',
            'integer'  => 'El cliente seleccionado no es válido',
        ],
        'fecha' => [
            'required'   => 'La fecha es obligatoria',
            'valid_date' => 'La fecha debe tener un formato válido',
        ],
        'total' => [
            'required' => 'El total es obligatorio',
            'numeric'  => 'El total debe ser un valor numérico',
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list'  => 'El estado debe ser completada, anulada o pendiente',
        ],
    ];
    protected $skipValidation       = false;

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo('ClienteModel::class', 'cliente_id');
    }

    public function detalles()
    {
        return $this->hasMany('DetalleVentaModel::class', 'venta_id');
    }

    //Método para generar el código de venta automáticamente
    // Método para generar código único de venta
    public function generarCodigo()
    {
        $fecha = date('Ymd');
        $ultimaVenta = $this->select('codigo')
            ->like('codigo', "V-$fecha")
            ->orderBy('id', 'DESC')
            ->first();

        if ($ultimaVenta) {
            $ultimoNumero = (int)substr($ultimaVenta['codigo'], -3);
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }

        return "V-$fecha-" . str_pad($nuevoNumero, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Calcula el valor real de una venta
     * 
     * @param array $ventaData Datos de la venta
     * @return float Valor real calculado
     */

    public function calcularValorReal(array $ventaData)
    {
        $valorVenta = (float)($ventaData['total'] ?? 0);
        $comision = (float)($ventaData['comision'] ?? 0);
        $comisionFija = (float)($ventaData['comision_fija'] ?? 0);
        $reteiva = (float)($ventaData['reteiva'] ?? 0);
        $wompiComision = (float)($ventaData['wompi_comision'] ?? 0);
        $wompiIva = (float)($ventaData['wompi_iva'] ?? 0);

        // Fórmula: valor venta – (Comisión + Comisión Fija + reteiva + Wompi comisión + Wompi IVA)
        $valorReal = $valorVenta - ($comision + $comisionFija + $reteiva + $wompiComision + $wompiIva);

        // Asegurar que el valor real no sea negativo
        return max(0, $valorReal);
    }
}
