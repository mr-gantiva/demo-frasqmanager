<?= $this->extend('templates/header') ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2"><?= $title ?></h1>
        <div>
            <?php if ($venta['estado'] !== 'anulada'): ?>
                <a href="<?= base_url('ventas/anular/' . $venta['id']) ?>" class="btn btn-danger me-2"
                    onclick="return confirm('¿Está seguro de que desea anular la venta <?= $venta['codigo'] ?>? \nEsta acción reintegrará los productos al inventario.');">
                    <i class="fas fa-ban"></i> Anular Venta
                </a>
            <?php endif; ?>
            <a href="<?= base_url('ventas') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Modal de confirmación para anular -->
    <div class="modal fade" id="anularModal" tabindex="-1" aria-labelledby="anularModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="anularModalLabel">Confirmar anulación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de que desea anular la venta <strong><?= $venta['codigo'] ?></strong>?</p>
                    <p class="text-danger"><small>Esta acción reintegrará los productos al inventario y no se podrá deshacer.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="<?= base_url('ventas/anular/' . $venta['id']) ?>" class="btn btn-danger">Anular Venta</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información de la venta -->
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Información de la Venta</h5>
                    <span class="badge <?= $venta['estado'] === 'completada' ? 'bg-success' : ($venta['estado'] === 'anulada' ? 'bg-danger' : 'bg-warning') ?>">
                        <?= ucfirst($venta['estado']) ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Código:</th>
                                    <td><?= $venta['codigo'] ?></td>
                                </tr>
                                <tr>
                                    <th>Fecha:</th>
                                    <td><?= date('d/m/Y', strtotime($venta['fecha'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Cliente:</th>
                                    <td>
                                        <a href="<?= base_url('clientes/view/' . $venta['cliente_id']) ?>">
                                            <?= $venta['cliente_nombre'] . ' ' . $venta['cliente_apellido'] ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Estado de Venta:</th>
                                    <td>
                                        <span class="badge bg-info"><?= $venta['estado_venta'] ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Canal:</th>
                                    <td>
                                        <?php if (!empty($venta['canal_nombre'])): ?>
                                            <span class="badge" style="background-color: <?= $venta['canal_color'] ?>">
                                                <?= $venta['canal_nombre'] ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">No especificado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tipo de Envío:</th>
                                    <td><?= $venta['tipo_envio'] ?></td>
                                </tr>
                                <tr>
                                    <th>Tipo de Publicación:</th>
                                    <td><?= $venta['tipo_publicacion'] ?></td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Estado de Pago:</th>
                                    <td>
                                        <span class="badge <?= $venta['estado_pago'] === 'Cancelado' ? 'bg-success' : 'bg-warning' ?>">
                                            <?= $venta['estado_pago'] ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Banco:</th>
                                    <td>
                                        <?= $venta['banco'] ?>
                                        <?php if ($venta['banco'] === 'Pendiente consignar' && $venta['fecha_actualizacion_banco']): ?>
                                            <?php
                                            $fechaActualizacion = new DateTime($venta['fecha_actualizacion_banco']);
                                            $hoy = new DateTime();
                                            $diff = $fechaActualizacion->diff($hoy);
                                            if ($diff->days > 0):
                                            ?>
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    Pendiente desde hace <?= $diff->days ?> día(s)
                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Factura Electrónica:</th>
                                    <td>
                                        <?= $venta['factura_electronica'] ?: '<span class="text-muted">No registrada</span>' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Venta:</th>
                                    <td class="fw-bold">$<?= number_format($venta['total'], 2, '.', ',') ?></td>
                                </tr>
                                <tr>
                                    <th>Valor del Envío:</th>
                                    <td>$<?= number_format($venta['valor_envio'], 2, '.', ',') ?></td>
                                </tr>
                                <tr>
                                    <th>Valor Real:</th>
                                    <td class="fw-bold text-success">$<?= number_format($venta['valor_real'], 2, '.', ',') ?></td>
                                </tr>
                                <?php if (!empty($venta['notas'])): ?>
                                    <tr>
                                        <th>Notas:</th>
                                        <td><?= $venta['notas'] ?></td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--detalles financieros -->
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detalles Financieros</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Comisión:</th>
                                    <td>$<?= number_format($venta['comision'], 2, '.', ',') ?></td>
                                </tr>
                                <tr>
                                    <th>Comisión Fija:</th>
                                    <td>$<?= number_format($venta['comision_fija'], 2, '.', ',') ?></td>
                                </tr>
                                <tr>
                                    <th>Reteiva 15%:</th>
                                    <td>$<?= number_format($venta['reteiva'], 2, '.', ',') ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Wompi Comisión:</th>
                                    <td>$<?= number_format($venta['wompi_comision'], 2, '.', ',') ?></td>
                                </tr>
                                <tr>
                                    <th>Wompi IVA:</th>
                                    <td>$<?= number_format($venta['wompi_iva'], 2, '.', ',') ?></td>
                                </tr>
                                <tr>
                                    <th>Total Descuentos:</th>
                                    <td class="fw-bold text-danger">
                                        $<?= number_format($venta['comision'] + $venta['comision_fija'] + $venta['reteiva'] + $venta['wompi_comision'] + $venta['wompi_iva'], 2, '.', ',') ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detalles de la venta -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detalle de Productos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Precio</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($detalles as $detalle): ?>
                                    <tr>
                                        <td>
                                            <strong><?= $detalle['producto_nombre'] ?></strong>
                                            <br>
                                            <small class="text-muted">Código: <?= $detalle['producto_codigo'] ?></small>
                                        </td>
                                        <td class="text-center">$<?= number_format($detalle['precio_unitario'], 2, '.', ',') ?></td>
                                        <td class="text-center"><?= $detalle['cantidad'] ?></td>
                                        <td class="text-end">$<?= number_format($detalle['subtotal'], 2, '.', ',') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th class="text-end">$<?= number_format($venta['total'], 2, '.', ',') ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('templates/footer') ?>