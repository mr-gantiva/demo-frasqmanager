<?= $this->extend('templates/header') ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2"><?= $title ?></h1>
        <a href="<?= base_url('ventas/new') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Venta
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Canal</th>
                            <th>Estado</th>
                            <th>Total Venta</th>
                            <th>Valor Real</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ventas)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No hay ventas registradas</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ventas as $venta): ?>
                                <tr>
                                    <td><?= $venta['codigo'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($venta['fecha'])) ?></td>
                                    <td><?= $venta['cliente_nombre'] . ' ' . $venta['cliente_apellido'] ?></td>
                                    <td>
                                        <?php if (!empty($venta['canal_nombre'])): ?>
                                            <span class="badge" style="background-color: <?= $venta['canal_color'] ?>">
                                                <?= $venta['canal_nombre'] ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">No especificado</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if ($venta['estado'] === 'completada'): ?>
                                            <span class="badge bg-success">Completada</span>
                                        <?php elseif ($venta['estado'] === 'anulada'): ?>
                                            <span class="badge bg-danger">Anulada</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Pendiente</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>$<?= number_format($venta['total'], 2, '.', ',') ?></td>

                                    <td>$<?= number_format($venta['valor_real'], 2, '.', ',') ?></td>
                                    <td>
                                        <a href="<?= base_url('ventas/view/' . $venta['id']) ?>" class="btn btn-sm btn-info" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <?php if ($venta['estado'] !== 'anulada'): ?>
                                            <a href="<?= base_url('ventas/anular/' . $venta['id']) ?>" class="btn btn-sm btn-danger"
                                                onclick="return confirm('¿Está seguro de que desea anular la venta <?= $venta['codigo'] ?>? Esta acción reintegrará los productos al inventario.');">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                        <?php endif; ?>
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

<?= $this->include('templates/footer') ?>