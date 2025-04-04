<?= $this->extend('templates/header') ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2"><?= $title ?></h1>
        <div>
            <button type="button" class="btn btn-outline-secondary me-2" onclick="exportToPDF()">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </button>
            <a href="<?= base_url('reportes') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Filtros</h5>
        </div>
        <div class="card-body">
            <form action="<?= base_url('reportes/ventas') ?>" method="get" class="row g-3">
                <div class="col-md-3">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= $fechaInicio ?>">
                </div>
                <div class="col-md-3">
                    <label for="fecha_fin" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?= $fechaFin ?>">
                </div>
                <div class="col-md-3">
                    <label for="cliente_id" class="form-label">Cliente</label>
                    <select class="form-select" id="cliente_id" name="cliente_id">
                        <option value="">Todos los clientes</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= $cliente['id'] ?>" <?= $clienteId == $cliente['id'] ? 'selected' : '' ?>>
                                <?= $cliente['nombre'] . ' ' . $cliente['apellido'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="canal" class="form-label">Canal de Venta</label>
                    <select class="form-select" id="canal" name="canal">
                        <option value="">Todos los canales</option>
                        <?php foreach ($canales as $canal): ?>
                            <option value="<?= $canal['id'] ?>" <?= $canalId == $canal['id'] ? 'selected' : '' ?>>
                                <?= $canal['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="<?= base_url('reportes/ventas') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-sync-alt"></i> Restablecer
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Resumen -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Resumen</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3">
                                <p class="text-muted mb-1">Total de Ventas</p>
                                <h3 class="mb-0"><?= $totalVentas ?></h3>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3">
                                <p class="text-muted mb-1">Monto Total</p>
                                <h3 class="mb-0">$<?= number_format($totalMonto, 2, '.', ',') ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <p class="text-muted mb-1">Periodo</p>
                                <h5 class="mb-0"><?= date('d/m/Y', strtotime($fechaInicio)) ?> - <?= date('d/m/Y', strtotime($fechaFin)) ?></h5>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <p class="text-muted mb-1">Promedio por Venta</p>
                                <h5 class="mb-0">$<?= $totalVentas > 0 ? number_format($totalMonto / $totalVentas, 2, '.', ',') : '0.00' ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Gráfico de Ventas</h5>
                </div>
                <div class="card-body">
                    <canvas id="ventasChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de ventas -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Detalle de Ventas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tablaVentas">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Canal</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ventas)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay ventas que coincidan con los filtros</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ventas as $venta): ?>
                                <tr>
                                    <td><?= $venta['codigo'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($venta['fecha'])) ?></td>
                                    <td><?= $venta['cliente_nombre'] . ' ' . $venta['cliente_apellido'] ?></td>
                                    <td><?= $venta['canal_nombre'] ?? 'N/A' ?></td>
                                    <td>$<?= number_format($venta['total'], 2, '.', ',') ?></td>
                                    <td>
                                        <?php if ($venta['estado'] === 'completada'): ?>
                                            <span class="badge bg-success">Completada</span>
                                        <?php elseif ($venta['estado'] === 'anulada'): ?>
                                            <span class="badge bg-danger">Anulada</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Pendiente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('ventas/view/' . $venta['id']) ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ChartJS y DataTables -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DataTable para ventas
        $('#tablaVentas').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
            },
            pageLength: 10,
            order: [
                [1, 'desc']
            ]
        });

        // Preparar datos para el gráfico
        const ventas = <?= json_encode($ventas) ?>;
        const ventasPorDia = {};

        // Agrupar ventas por día
        ventas.forEach(venta => {
            if (venta.estado === 'completada') {
                const fecha = venta.fecha;
                if (!ventasPorDia[fecha]) {
                    ventasPorDia[fecha] = 0;
                }
                ventasPorDia[fecha] += parseFloat(venta.total);
            }
        });

        // Convertir a arrays para Chart.js
        const fechas = Object.keys(ventasPorDia).sort();
        const montos = fechas.map(fecha => ventasPorDia[fecha]);

        // Formatear fechas para mostrar
        const fechasFormateadas = fechas.map(fecha => {
            const date = new Date(fecha);
            return date.toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit'
            });
        });

        // Crear gráfico
        const ctx = document.getElementById('ventasChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: fechasFormateadas,
                datasets: [{
                    label: 'Ventas diarias',
                    data: montos,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
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
    });

    // Función para exportar a PDF (puedes implementarla con jsPDF o enviar al servidor)
    function exportToPDF() {
        alert('Funcionalidad de exportación a PDF estará disponible próximamente');
    }
</script>

<?= $this->include('templates/footer') ?>