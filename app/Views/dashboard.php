<?= $this->extend('templates/header') ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Dashboard</h1>
        <div>
            <a href="<?= base_url('ventas/new') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Venta
            </a>
            <?php if (auth()->user() && in_array('admin', auth()->user()->getGroups())): ?>
                <a href="<?= base_url('reportes') ?>" class="btn btn-info ms-2">
                    <i class="fas fa-chart-line"></i> Ver Reportes
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="row mb-4">
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-bold mb-1">Productos</h6>
                            <h2 class="mb-0"><?= $total_productos ?></h2>
                        </div>
                        <i class="fas fa-box fa-3x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?= base_url('productos') ?>" class="text-white">Ver detalles</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-bold mb-1">Clientes</h6>
                            <h2 class="mb-0"><?= $total_clientes ?></h2>
                        </div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?= base_url('clientes') ?>" class="text-white">Ver detalles</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-dark h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-bold mb-1">Ventas Realizadas</h6>
                            <h2 class="mb-0"><?= $total_ventas ?></h2>
                        </div>
                        <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?= base_url('ventas') ?>" class="text-dark">Ver detalles</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-bold mb-1">Ingresos Totales</h6>
                            <h2 class="mb-0">$<?= number_format($ventas_total, 2, '.', ',') ?></h2>
                        </div>
                        <i class="fas fa-dollar-sign fa-3x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?= base_url('ventas') ?>" class="text-white">Ver detalles</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gráfico de ventas -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Ventas por Mes</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= base_url('reportes/ventas') ?>">Ver reporte completo</a></li>
                            <li><a class="dropdown-item" href="#">Exportar datos</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="ventasPorMesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos con bajo stock -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Productos con Bajo Stock</h5>
                    <a href="<?= base_url('productos') ?>" class="btn btn-sm btn-outline-primary">Ver todos</a>
                </div>
                <div class="card-body">
                    <?php if (empty($productos_bajo_stock)): ?>
                        <p class="text-center text-muted py-3">No hay productos con bajo stock</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Stock</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($productos_bajo_stock as $producto): ?>
                                        <tr>
                                            <td><?= $producto['nombre'] ?></td>
                                            <td>
                                                <span class="badge <?= $producto['stock'] < 5 ? 'bg-danger' : 'bg-warning' ?>">
                                                    <?= $producto['stock'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('productos/edit/' . $producto['id']) ?>" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Productos más vendidos -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Productos más Vendidos</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($productos_mas_vendidos)): ?>
                        <p class="text-center text-muted py-3">No hay datos disponibles</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Unidades</th>
                                        <th>Total Ventas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($productos_mas_vendidos as $producto): ?>
                                        <tr>
                                            <td>
                                                <?= $producto['nombre'] ?>
                                                <small class="text-muted d-block"><?= $producto['codigo'] ?></small>
                                            </td>
                                            <td><?= $producto['total_vendido'] ?></td>
                                            <td>$<?= number_format($producto['total_ventas'], 2, '.', ',') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Ventas recientes -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ventas Recientes</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($ventas_recientes)): ?>
                        <p class="text-center text-muted py-3">No hay ventas registradas</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ventas_recientes as $venta): ?>
                                        <tr>
                                            <td>
                                                <a href="<?= base_url('ventas/view/' . $venta['id']) ?>">
                                                    <?= $venta['codigo'] ?>
                                                </a>
                                            </td>
                                            <td><?= $venta['cliente_nombre'] . ' ' . $venta['cliente_apellido'] ?></td>
                                            <td><?= date('d/m/Y', strtotime($venta['fecha'])) ?></td>
                                            <td>$<?= number_format($venta['total'], 2, '.', ',') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Datos para el gráfico de ventas por mes
        const ventasPorMes = <?= json_encode($ventas_por_mes) ?>;

        if (ventasPorMes && ventasPorMes.length > 0) {
            // Preparar datos para el gráfico
            const labels = ventasPorMes.map(item => {
                const [year, month] = item.mes.split('-');
                const date = new Date(year, month - 1);
                return date.toLocaleDateString('es-ES', {
                    month: 'short',
                    year: 'numeric'
                });
            });

            const data = ventasPorMes.map(item => parseFloat(item.total));

            // Crear gráfico de ventas por mes
            const ctx = document.getElementById('ventasPorMesChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Ventas ($)',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('es-CO', {
                                            style: 'currency',
                                            currency: 'COP'
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toFixed(2);
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>

<?= $this->include('templates/footer') ?>