<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VentaModel;
use App\Models\DetalleVentaModel;
use App\Models\ClienteModel;
use App\Models\ProductoModel;
use App\Helpers\CanalesVenta;

class Ventas extends BaseController
{
    protected $ventaModel;
    protected $detalleVentaModel;
    protected $clienteModel;
    protected $productoModel;
    protected $db;

    public function __construct()
    {
        $this->ventaModel = new VentaModel();
        $this->detalleVentaModel = new DetalleVentaModel();
        $this->clienteModel = new ClienteModel();
        $this->productoModel = new ProductoModel();
        $this->db = \Config\Database::connect();
    }
    public function index()
    {
        $data = [
            'title' => 'Gestión de Ventas',
            'ventas' => $this->ventaModel->select('ventas.*, clientes.nombre as cliente_nombre, clientes.apellido as cliente_apellido, canales_venta.nombre as canal_nombre, canales_venta.color as canal_color')
                ->join('clientes', 'clientes.id = ventas.cliente_id')
                ->join('canales_venta', 'canales_venta.id = ventas.canal_venta_id', 'left')
                ->orderBy('ventas.created_at', 'DESC')
                ->findAll()
        ];

        return view('ventas/index', $data);
    }

    public function new()
    {
        // Obtener canales de venta directamente
        $db = db_connect();
        $canalesVenta = [];

        // Intentar obtener los canales de la tabla canales_venta
        try {
            $canalesVenta = $db->table('canales_venta')
                ->where('activo', 1)
                ->get()->getResultArray();

            $canalesVenta = array_column($canalesVenta, 'nombre', 'id');
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener canales: ' . $e->getMessage());
            $canalesVenta = [
                '1' => 'Sitio web',
                '2' => 'Mercadolibre',
                '3' => 'Rappi',
                '4' => 'Falabella',
                '5' => 'WhatsApp'
            ];
        }

        // Opciones para los campos de selección
        $estadosVenta = [
            'En alistamiento' => 'En alistamiento',
            'En ruta' => 'En ruta',
            'Entregado' => 'Entregado'
        ];

        $tiposEnvio = [
            'A cargo del comprador' => 'A cargo del comprador',
            'Flex' => 'Flex',
            'A cargo del Frasquerio' => 'A cargo del Frasquerio',
            'Retiro en bodega' => 'Retiro en bodega'
        ];

        $tiposPublicacion = [
            'clásica' => 'Clásica',
            'premium' => 'Premium'
        ];

        $estadosPago = [
            'Cancelado' => 'Cancelado',
            'Abono' => 'Abono',
            'En proceso Mercadopago' => 'En proceso Mercadopago',
            'Liberado Mercadopago' => 'Liberado Mercadopago',
            'Mercadopago a Bancolombia' => 'Mercadopago a Bancolombia'
        ];

        $bancos = [
            'Nequi' => 'Nequi',
            'Daviplata' => 'Daviplata',
            'Bancolombia' => 'Bancolombia',
            'Mercadopago' => 'Mercadopago',
            'Pendiente consignar' => 'Pendiente consignar'
        ];

        $data = [
            'title' => 'Nueva Venta',
            'clientes' => $this->clienteModel->findAll(),
            'productos' => $this->productoModel->where('stock >', 0)->findAll(),
            'codigo_venta' => $this->ventaModel->generarCodigo(),
            'fecha' => date('Y-m-d'),
            'canales_venta' => $canalesVenta,
            'estados_venta' => $estadosVenta,
            'tipos_envio' => $tiposEnvio,
            'tipos_publicacion' => $tiposPublicacion,
            'estados_pago' => $estadosPago,
            'bancos' => $bancos
        ];

        return view('ventas/new', $data);
    }

    public function create()
    {
        // Iniciar transacción
        $this->db->transBegin();

        try {
            // Depuración de datos recibidos
            log_message('debug', 'POST recibido: ' . json_encode($this->request->getPost()));

            // Validación básica
            if (!$this->request->getPost('cliente_id') || !$this->request->getPost('total')) {
                throw new \Exception('Faltan datos requeridos: cliente o total');
            }

            // Recoger todos los campos del formulario
            $ventaData = [
                'codigo' => $this->request->getPost('codigo'),
                'cliente_id' => $this->request->getPost('cliente_id'),
                'fecha' => $this->request->getPost('fecha'),
                'total' => $this->request->getPost('total'),
                'estado' => 'completada',
                'estado_venta' => $this->request->getPost('estado_venta'),
                'comision' => $this->request->getPost('comision') ?: 0,
                'comision_fija' => $this->request->getPost('comision_fija') ?: 0,
                'valor_envio' => $this->request->getPost('valor_envio') ?: 0,
                'tipo_envio' => $this->request->getPost('tipo_envio'),
                'tipo_publicacion' => $this->request->getPost('tipo_publicacion'),
                'reteiva' => $this->request->getPost('reteiva') ?: 0,
                'wompi_comision' => $this->request->getPost('wompi_comision') ?: 0,
                'wompi_iva' => $this->request->getPost('wompi_iva') ?: 0,
                'factura_electronica' => $this->request->getPost('factura_electronica'),
                'estado_pago' => $this->request->getPost('estado_pago'),
                'banco' => $this->request->getPost('banco'),
                'notas' => $this->request->getPost('notas')
            ];

            // Si el banco es "Pendiente consignar", registrar la fecha actual
            if ($ventaData['banco'] === 'Pendiente consignar') {
                $ventaData['fecha_actualizacion_banco'] = date('Y-m-d');
            }

            // Verificar si se seleccionó un canal de venta
            $canalVentaId = $this->request->getPost('canal_venta_id');
            if (!empty($canalVentaId)) {
                $ventaData['canal_venta_id'] = (int)$canalVentaId;
                log_message('debug', 'Canal de venta seleccionado: ' . $canalVentaId);
            } else {
                log_message('debug', 'No se seleccionó canal de venta');
            }

            // Calcular el valor real
            $ventaData['valor_real'] = $this->ventaModel->calcularValorReal($ventaData);

            // Log para depuración
            log_message('debug', 'Datos de venta a insertar: ' . json_encode($ventaData));

            // Guardar venta directamente con el query builder
            $resultado = $this->db->table('ventas')->insert($ventaData);

            if (!$resultado) {
                throw new \Exception('Error al insertar la venta: ' . print_r($this->db->error(), true));
            }

            $ventaId = $this->db->insertID();

            if (!$ventaId) {
                throw new \Exception('No se pudo obtener el ID de la venta insertada');
            }

            log_message('debug', 'Venta creada con ID: ' . $ventaId);

            // Productos de la venta
            $productosIds = $this->request->getPost('producto_id');
            $cantidades = $this->request->getPost('cantidad');
            $precios = $this->request->getPost('precio');
            $subtotales = $this->request->getPost('subtotal');

            // Verificar que tengamos productos
            if (empty($productosIds) || !is_array($productosIds)) {
                throw new \Exception('No se recibieron productos para la venta');
            }

            log_message('debug', 'Productos recibidos: ' . count($productosIds));

            // Guardar detalles y actualizar stock
            for ($i = 0; $i < count($productosIds); $i++) {
                // Verificar datos válidos
                if (empty($productosIds[$i])) continue;

                // Guardar detalle
                $detalleData = [
                    'venta_id' => $ventaId,
                    'producto_id' => $productosIds[$i],
                    'cantidad' => $cantidades[$i],
                    'precio_unitario' => $precios[$i],
                    'subtotal' => $subtotales[$i]
                ];

                $resultadoDetalle = $this->db->table('detalles_venta')->insert($detalleData);

                if (!$resultadoDetalle) {
                    throw new \Exception('Error al insertar detalle de venta: ' . print_r($this->db->error(), true));
                }

                // Obtener producto actual
                $producto = $this->db->table('productos')
                    ->where('id', $productosIds[$i])
                    ->get()->getRowArray();

                if (!$producto) {
                    throw new \Exception("Producto con ID {$productosIds[$i]} no encontrado");
                }

                // Calcular nuevo stock
                $stockActual = (int)$producto['stock'];
                $nuevoStock = $stockActual - (int)$cantidades[$i];

                if ($nuevoStock < 0) {
                    throw new \Exception("Stock insuficiente para el producto '{$producto['nombre']}'. Stock actual: $stockActual, Solicitado: {$cantidades[$i]}");
                }

                log_message('debug', "Actualizando stock de producto ID: $productosIds de $stockActual a $nuevoStock");

                $this->db->table('productos')
                    ->where('id', $productosIds[$i])
                    ->update(['stock' => $nuevoStock]);
            }

            // Confirmar transacción
            $this->db->transCommit();

            log_message('debug', 'Venta registrada con éxito. ID: ' . $ventaId);

            return redirect()->to('/ventas')->with('message', 'Venta registrada exitosamente');
        } catch (\Exception $e) {
            // Revertir transacción
            $this->db->transRollback();

            log_message('error', 'Error al registrar venta: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Error al registrar la venta: ' . $e->getMessage());
        }
    }

    public function view($id = null)
    {
        $venta = $this->ventaModel->select('ventas.*, clientes.nombre as cliente_nombre, clientes.apellido as cliente_apellido, clientes.identificacion as cliente_identificacion, clientes.tipo_identificacion as cliente_tipo_identificacion, canales_venta.nombre as canal_nombre, canales_venta.color as canal_color')
            ->join('clientes', 'clientes.id = ventas.cliente_id')
            ->join('canales_venta', 'canales_venta.id = ventas.canal_venta_id', 'left')
            ->find($id);

        if (!$venta) {
            return redirect()->to('/ventas')->with('error', 'Venta no encontrada');
        }

        $detalles = $this->detalleVentaModel->select('detalles_venta.*, productos.nombre as producto_nombre, productos.codigo as producto_codigo')
            ->join('productos', 'productos.id = detalles_venta.producto_id')
            ->where('venta_id', $id)
            ->findAll();

        $data = [
            'title' => 'Detalle de Venta',
            'venta' => $venta,
            'detalles' => $detalles,
            'canales_venta' => CanalesVenta::getCanales()
        ];

        return view('ventas/view', $data);
    }


    public function anular($id = null)
    {
        // Agregar logs para rastrear el proceso
        log_message('info', "Iniciando anulación de venta ID: $id");

        // Iniciar transacción
        $this->db->transBegin();

        try {

            // Verificar que la venta exista
            $venta = $this->ventaModel->find($id);

            if (!$venta) {
                log_message('error', "Venta ID $id no encontrada");
                return redirect()->to('/ventas')->with('error', 'Venta no encontrada');
            }

            // Si ya está anulada, no hacer nada
            if ($venta['estado'] === 'anulada') {
                log_message('info', "Venta ID $id ya está anulada");
                return redirect()->to('/ventas')->with('error', 'La venta ya está anulada');
            }

            // Obtener detalles de la venta directamente de la base de datos para asegurar datos frescos
            $detalles = $this->db->table('detalles_venta')
                ->where('venta_id', $id)
                ->get()->getResultArray();


            log_message('info', "Detalles de venta encontrados: " . count($detalles));

            if (empty($detalles)) {
                log_message('warning', "No se encontraron detalles para la venta ID $id");
                return redirect()->to('/ventas')->with('error', 'No se encontraron detalles para esta venta');
            }

            // Restaurar stock de cada producto
            foreach ($detalles as $detalle) {
                // Obtener el producto actual
                $producto_id = $detalle['producto_id'];
                $cantidad = (int)$detalle['cantidad'];

                // Obtener el stock actual directamente de la base de datos
                $producto = $this->db->table('productos')
                    ->select('stock, nombre')
                    ->where('id', $producto_id)
                    ->get()->getRowArray();

                if ($producto) {
                    $stockActual = (int)$producto['stock'];
                    $nuevoStock = $stockActual + $cantidad;

                    log_message('info', "Actualizando producto ID $producto_id: Stock actual: $stockActual, Cantidad a devolver: $cantidad, Nuevo stock: $nuevoStock");

                    // Actualizar directamente con query builder para evitar problemas con el modelo
                    $resultado = $this->db->table('productos')
                        ->set('stock', $nuevoStock)
                        ->where('id', $producto_id)
                        ->update();

                    if (!$resultado) {
                        log_message('error', "Error al actualizar stock del producto ID $producto_id");
                        throw new \Exception('Error al actualizar stock del producto');
                    }
                } else {
                    log_message('warning', "Producto ID $producto_id no encontrado");
                }
            }

            // Anular la venta - actualizar directamente con query builde
            $resultadoVenta = $this->db->table('ventas')
                ->set('estado', 'anulada')
                ->where('id', $id)
                ->update();

            if (!$resultadoVenta) {
                log_message('error', "Error al actualizar estado de la venta ID $id");
                throw new \Exception('Error al anular la venta');
            }

            // Confirmar transacción
            $this->db->transCommit();
            log_message('info', "Venta ID $id anulada correctamente");
            return redirect()->to('/ventas')->with('message', 'Venta anulada exitosamente. Stock de productos actualizado.');
        } catch (\Exception $e) {
            // Revertir transacción
            $this->db->transRollback();
            log_message('error', "Excepción al anular venta ID $id: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error al anular la venta: ' . $e->getMessage());
        }
    }


    // Método para obtener información de un producto vía AJAX
    public function getProducto()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        $productoId = $this->request->getGet('id');
        $producto = $this->productoModel->find($productoId);

        if (!$producto) {
            return $this->response->setJSON(['error' => 'Producto no encontrado'])->setStatusCode(404);
        }

        return $this->response->setJSON($producto);
    }

    public function probarAnulacion($id = null)
    {
        echo "Probando anulación de venta ID: $id<br>";

        try {
            $venta = $this->ventaModel->find($id);

            if (!$venta) {
                echo "Error: Venta no encontrada<br>";
                return;
            }

            echo "Estado actual: " . $venta['estado'] . "<br>";

            // Intentar actualizar directamente
            $actualizado = $this->ventaModel->save([
                'id' => $id,
                'estado' => 'anulada'
            ]);

            echo "Actualización realizada: " . ($actualizado ? "Sí" : "No") . "<br>";

            // Verificar de nuevo
            $ventaNueva = $this->ventaModel->find($id);
            echo "Nuevo estado: " . $ventaNueva['estado'] . "<br>";
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function testUpdateStock($productoId, $cantidad)
    {
        // Obtener producto actual
        $producto = $this->productoModel->find($productoId);

        if (!$producto) {
            echo "Producto no encontrado";
            return;
        }

        echo "Producto: " . $producto['nombre'] . "<br>";
        echo "Stock actual: " . $producto['stock'] . "<br>";

        // Calcular nuevo stock
        $nuevoStock = $producto['stock'] + $cantidad;
        echo "Nuevo stock calculado: " . $nuevoStock . "<br>";

        // Intentar actualizar
        $resultado = $this->productoModel->update($productoId, [
            'stock' => $nuevoStock
        ]);

        echo "Resultado de actualización: " . ($resultado ? "Éxito" : "Falló") . "<br>";

        if (!$resultado) {
            echo "Errores: " . print_r($this->productoModel->errors(), true) . "<br>";
        }

        // Verificar cambio
        $productoActualizado = $this->productoModel->find($productoId);
        echo "Stock después de actualizar: " . $productoActualizado['stock'] . "<br>";
    }
}
