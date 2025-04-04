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
            <form action="<?= base_url('reportes/productos') ?>" method="get" class="row g-3">
                <div class="col-md-4">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= $fechaInicio ?>">
                </div>
                <div class="col-md-4">
                    <label for="fecha_fin" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?= $fechaFin ?>">
                </div>
                <div class="col-md-4">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select class="form-select" id="categoria" name="categoria">
                        <option value="">Todas las categorías</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['categoria'] ?>" <?= $categoriaSeleccionada == $cat['categoria'] ? 'selected' : '' ?>>
                                <?= $cat['categoria'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="<?= base_url('reportes/productos') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-sync-alt"></i> Restablecer
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top 5 Productos más Vendidos</h5>
                </div>
                <div class="card-body">
                    <canvas id="topProductosChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Distribución de Ventas por Categoría</h5>
                </div>
                <div class="card-body">
                    <canvas id="categoriaChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de productos -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Detalle de Productos Vendidos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tablaProductos">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Unidades Vendidas</th>
                            <th>Total Ventas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($productos)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay datos que coincidan con los filtros</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($productos as $producto): ?>
                                <tr>
                                    <td><?= $producto['codigo'] ?></td>
                                    <td><?= $producto['nombre'] ?></td>
                                    <td><?= $producto['categoria'] ?? 'Sin categoría' ?></td>
                                    <td><?= $producto['total_vendido'] ?></td>
                                    <td>$<?= number_format($producto['total_ventas'], 2, '.', ',') ?></td>
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
        // DataTable para productos
        $('#tablaProductos').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
            },
            pageLength: 10,
            order: [
                [3, 'desc']
            ]
        });

        // Datos para gráficos
        const productos = <?= json_encode($productos) ?>;

        if (productos.length > 0) {
            // Gráfico de Top 5 productos
            const topProductos = productos.slice(0, 5);
            const nombresProductos = topProductos.map(p => p.nombre);
            const cantidadesVendidas = topProductos.map(p => p.total_vendido);

            const ctxTop = document.getElementById('topProductosChart').getContext('2d');
            new Chart(ctxTop, {
                type: 'bar',
                data: {
                    labels: nombresProductos,
                    datasets: [{
                        label: 'Unidades Vendidas',
                        data: cantidadesVendidas,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(153, 102, 255, 0.5)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Gráfico por categoría
            const categorias = {};
            productos.forEach(p => {
                const categoria = p.categoria || 'Sin categoría';
                if (!categorias[categoria]) {
                    categorias[categoria] = 0;
                }
                categorias[categoria] += parseFloat(p.total_ventas);
            });

            const nombresCategorias = Object.keys(categorias);
            const ventasPorCategoria = Object.values(categorias);

            const ctxCat = document.getElementById('categoriaChart').getContext('2d');
            new Chart(ctxCat, {
                type: 'pie',
                data: {
                    labels: nombresCategorias,
                    datasets: [{
                        data: ventasPorCategoria,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(153, 102, 255, 0.5)',
                            'rgba(255, 159, 64, 0.5)',
                            'rgba(201, 203, 207, 0.5)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(201, 203, 207, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
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
        }
    });

    // Función para exportar a PDF
    function exportToPDF() {
        alert('Funcionalidad de exportación a PDF estará disponible próximamente');
    }
</script>

<?= $this->include('templates/footer') ?>