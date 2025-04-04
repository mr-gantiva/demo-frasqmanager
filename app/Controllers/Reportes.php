<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VentaModel;
use App\Models\ProductoModel;
use App\Models\ClienteModel;
use App\Models\DetalleVentaModel;

class Reportes extends BaseController
{
    protected $ventaModel;
    protected $productoModel;
    protected $clienteModel;
    protected $detalleVentaModel;

    public function __construct()
    {
        $this->ventaModel = new VentaModel();
        $this->productoModel = new ProductoModel();
        $this->clienteModel = new ClienteModel();
        $this->detalleVentaModel = new DetalleVentaModel();
    }

    public function index()
    {
        // Verificar permisos
        if (!$this->verificarPermisoAdmin()) {
            return redirect()->to('/')->with('error', 'No tiene permisos para acceder a esta sección');
        }

        // Obtener datos para los reportes
        $totalVentas = $this->ventaModel->where('estado', 'completada')->countAllResults();
        $totalProductos = $this->productoModel->countAll();
        $totalClientes = $this->clienteModel->countAll();

        // Ventas por mes (último año)
        $ventasPorMes = $this->ventaModel
            ->select("DATE_FORMAT(fecha, '%Y-%m') as mes, SUM(total) as total")
            ->where('estado', 'completada')
            ->where('fecha >=', date('Y-m-d', strtotime('-1 year')))
            ->groupBy('mes')
            ->orderBy('mes', 'ASC')
            ->findAll();

        // Obtener canales de venta
        $db = db_connect();
        $canalesVenta = $db->table('ventas')
            ->select('canal_venta_id, COUNT(*) as total_ventas, SUM(total) as total_ingresos')
            ->where('estado', 'completada')
            ->groupBy('canal_venta_id')
            ->join('canales_venta', 'canales_venta.id = ventas.canal_venta_id', 'left')
            ->select('canales_venta.nombre as canal_nombre, canales_venta.color')
            ->get()->getResultArray();

        $data = [
            'title' => 'Reportes',
            'totalVentas' => $totalVentas,
            'totalProductos' => $totalProductos,
            'totalClientes' => $totalClientes,
            'ventasPorMes' => $ventasPorMes,
            'canalesVenta' => $canalesVenta
        ];

        return view('reportes/index', $data);
    }

    public function ventas()
    {
        if (!$this->verificarPermisoAdmin()) {
            return redirect()->to('/')->with('error', 'No tiene permisos para acceder a esta sección');
        }

        // Obtener parámetros de filtro
        $fechaInicio = $this->request->getGet('fecha_inicio') ?? date('Y-m-d', strtotime('-30 days'));
        $fechaFin = $this->request->getGet('fecha_fin') ?? date('Y-m-d');
        $clienteId = $this->request->getGet('cliente_id');
        $canal = $this->request->getGet('canal');

        // Construir la consulta base
        $builder = $this->ventaModel->select('ventas.*, clientes.nombre as cliente_nombre, clientes.apellido as cliente_apellido, canales_venta.nombre as canal_nombre')
            ->join('clientes', 'clientes.id = ventas.cliente_id')
            ->join('canales_venta', 'canales_venta.id = ventas.canal_venta_id', 'left')
            ->where('ventas.fecha >=', $fechaInicio)
            ->where('ventas.fecha <=', $fechaFin);

        // Aplicar filtros adicionales
        if (!empty($clienteId)) {
            $builder->where('ventas.cliente_id', $clienteId);
        }

        if (!empty($canal)) {
            $builder->where('ventas.canal_venta_id', $canal);
        }

        // Obtener resultados
        $ventas = $builder->findAll();

        // Calcular totales
        $totalVentas = count($ventas);
        $totalMonto = array_sum(array_column($ventas, 'total'));

        // Obtener listas para filtros
        $clientes = $this->clienteModel->orderBy('nombre', 'ASC')->findAll();
        $canales = db_connect()->table('canales_venta')->where('activo', 1)->get()->getResultArray();

        $data = [
            'title' => 'Reporte de Ventas',
            'ventas' => $ventas,
            'totalVentas' => $totalVentas,
            'totalMonto' => $totalMonto,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'clientes' => $clientes,
            'canales' => $canales,
            'clienteId' => $clienteId,
            'canalId' => $canal
        ];

        return view('reportes/ventas', $data);
    }

    public function productos()
    {
        if (!$this->verificarPermisoAdmin()) {
            return redirect()->to('/')->with('error', 'No tiene permisos para acceder a esta sección');
        }

        // Obtener parámetros de filtro
        $fechaInicio = $this->request->getGet('fecha_inicio') ?? date('Y-m-d', strtotime('-30 days'));
        $fechaFin = $this->request->getGet('fecha_fin') ?? date('Y-m-d');
        $categoria = $this->request->getGet('categoria');

        // Construir la consulta para obtener los productos más vendidos
        $db = db_connect();
        $builder = $db->table('detalles_venta')
            ->select('detalles_venta.producto_id, productos.nombre, productos.codigo, productos.categoria, SUM(detalles_venta.cantidad) as total_vendido, SUM(detalles_venta.subtotal) as total_ventas')
            ->join('productos', 'productos.id = detalles_venta.producto_id')
            ->join('ventas', 'ventas.id = detalles_venta.venta_id')
            ->where('ventas.estado', 'completada')
            ->where('ventas.fecha >=', $fechaInicio)
            ->where('ventas.fecha <=', $fechaFin);

        if (!empty($categoria)) {
            $builder->where('productos.categoria', $categoria);
        }

        $productos = $builder->groupBy('detalles_venta.producto_id')
            ->orderBy('total_vendido', 'DESC')
            ->get()->getResultArray();

        // Obtener categorías únicas para filtro
        $categorias = $db->table('productos')
            ->select('categoria')
            ->distinct()
            ->where('categoria IS NOT NULL')
            ->get()->getResultArray();

        $data = [
            'title' => 'Reporte de Productos',
            'productos' => $productos,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'categorias' => $categorias,
            'categoriaSeleccionada' => $categoria
        ];

        return view('reportes/productos', $data);
    }

    // Método auxiliar para verificar permisos
    private function verificarPermisoAdmin()
    {
        if (!auth()->loggedIn()) {
            return false;
        }

        $user = auth()->user();
        $grupos = $user->getGroups();
        return in_array('admin', $grupos);
    }
}
