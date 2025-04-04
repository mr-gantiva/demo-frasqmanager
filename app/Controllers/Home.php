<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\ProductoModel;
use App\Models\VentaModel;
use App\Models\DetalleVentaModel;

class Home extends BaseController
{
    public function index()
    {

        $productoModel = new ProductoModel();
        $clienteModel = new ClienteModel();
        $ventaModel = new VentaModel();
        $detalleVentaModel = new DetalleVentaModel();

        // Datos básicos para el dashboard
        $total_productos = $productoModel->countAll();
        $total_clientes = $clienteModel->countAll();
        $total_ventas = $ventaModel->where('estado', 'completada')->countAllResults();
        $ventas_total = $ventaModel->selectSum('total')
            ->where('estado', 'completada')
            ->first()['total'] ?? 0;

        // Productos con bajo stock (menos de 10 unidades)
        $productos_bajo_stock = $productoModel->where('stock <', 10)
            ->orderBy('stock', 'ASC')
            ->findAll();

        // Ventas recientes
        $ventas_recientes = $ventaModel->select('ventas.*, clientes.nombre as cliente_nombre, clientes.apellido as cliente_apellido')
            ->join('clientes', 'clientes.id = ventas.cliente_id')
            ->orderBy('ventas.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // Datos para gráfico de ventas por mes (últimos 6 meses)
        $ventas_por_mes = $ventaModel->select("DATE_FORMAT(fecha, '%Y-%m') as mes, SUM(total) as total")
            ->where('estado', 'completada')
            ->where('fecha >=', date('Y-m-d', strtotime('-6 months')))
            ->groupBy('mes')
            ->orderBy('mes', 'ASC')
            ->findAll();

        // Productos más vendidos
        $productos_mas_vendidos = $detalleVentaModel->select('detalles_venta.producto_id, productos.nombre, productos.codigo, SUM(detalles_venta.cantidad) as total_vendido, SUM(detalles_venta.subtotal) as total_ventas')
            ->join('productos', 'productos.id = detalles_venta.producto_id')
            ->join('ventas', 'ventas.id = detalles_venta.venta_id')
            ->where('ventas.estado', 'completada')
            ->groupBy('detalles_venta.producto_id')
            ->orderBy('total_vendido', 'DESC')
            ->limit(5)
            ->findAll();

        // Total de ventas (solo completadas)
        $totalVentas = $ventaModel->where('estado', 'completada')->countAllResults();

        // Suma total de ventas
        $ventasTotal = $ventaModel->selectSum('total')
            ->where('estado', 'completada')
            ->first()['total'] ?? 0;

        $data = [
            'title' => 'Dashboard - ERP Mi Negocio',
            'total_productos' => $total_productos,
            'total_clientes' => $total_clientes,
            'total_ventas' => $total_ventas,
            'ventas_total' => $ventas_total,
            'productos_bajo_stock' => $productos_bajo_stock,
            'ventas_recientes' => $ventas_recientes,
            'ventas_por_mes' => $ventas_por_mes,
            'productos_mas_vendidos' => $productos_mas_vendidos,
        ];
        return view('dashboard', $data);
    }
}
