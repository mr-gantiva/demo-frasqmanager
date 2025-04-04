<?= $this->extend('templates/header') ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2"><?= $title ?></h1>
        <div>
            <a href="<?= base_url('reportes/ventas') ?>" class="btn btn-primary me-2">
                <i class="fas fa-file-invoice-dollar"></i> Reporte de Ventas
            </a>
            <a href="<?= base_url('reportes/productos') ?>" class="btn btn-success me-2">
                <i class="fas fa-box"></i> Reporte de Productos
            </a>
            <a href="<?= base_url('/') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </div>

    <!-- Indicadores principales -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total de Ventas</h5>
                            <h2 class="display-4"><?= $totalVentas ?></h2>
                        </div>
                        <i class="fas fa-shopping-cart fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?= base_url('/ventas') ?>" class="text-white">Ver detalles</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total de Productos</h5>
                            <h2 class="display-4"><?= $totalProductos ?></h2>
                        </div>
                        <i class="fas fa-box fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?= base_url('/productos') ?>" class="text-white">Ver detalles</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total de Clientes</h5>
                            <h2 class="display-4"><?= $totalClientes ?></h2>
                        </div>
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?= base_url('/clientes') ?>" class="text-white">Ver detalles</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Ventas por Mes (Último Año)</h5>
                    <a href="<?= base_url('reportes/ventas') ?>" class="btn btn-sm btn-outline-primary">Ver reporte completo</a>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="ventasPorMesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Ventas por Canal</h5>
                    <a href="<?= base_url('reportes/ventas') ?>" class="btn btn-sm btn-outline-primary">Ver detalles</a>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="canalesVentaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informes Disponibles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-primary mb-3">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-file-invoice-dollar text-primary me-2"></i> Reporte de Ventas</h5>
                                    <p class="card-text">Analiza las ventas por periodo, cliente y canal de venta. Visualiza tendencias y compara rendimiento.</p>
                                    <a href="<?= base_url('reportes/ventas') ?>" class="btn btn-primary">Ver Reporte</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-success mb-3">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-box text-success me-2"></i> Reporte de Productos</h5>
                                    <p class="card-text">Analiza el desempeño de productos, identifica los más vendidos y evalúa el rendimiento por categoría.</p>
                                    <a href="<?= base_url('reportes/productos') ?>" class="btn btn-success">Ver Reporte</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gráfico de ventas por mes
        <?php if (isset($ventasPorMes) && !empty($ventasPorMes)): ?>
            const ventasPorMes = <?= json_encode($ventasPorMes) ?>;

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
            const ctxVentas = document.getElementById('ventasPorMesChart').getContext('2d');
            new Chart(ctxVentas, {
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
                                    return '$' + context.parsed.y.toFixed(2);
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
        <?php endif; ?>

        // Gráfico de canales de venta
        <?php if (isset($canalesVenta) && !empty($canalesVenta)): ?>
            const canalesVenta = <?= json_encode($canalesVenta) ?>;

            // Preparar datos para el gráfico
            const canalesLabels = canalesVenta.map(item => item.canal_nombre || 'No especificado');
            const canalesData = canalesVenta.map(item => parseFloat(item.total_ingresos));
            const canalesColores = canalesVenta.map(item => item.color || '#6c757d');

            // Crear gráfico de canales de venta
            const ctxCanales = document.getElementById('canalesVentaChart').getContext('2d');
            new Chart(ctxCanales, {
                type: 'doughnut',
                data: {
                    labels: canalesLabels,
                    datasets: [{
                        data: canalesData,
                        backgroundColor: canalesColores,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: $${value.toFixed(2)} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        <?php endif; ?>
    });
</script>

<?= $this->include('templates/footer') ?>