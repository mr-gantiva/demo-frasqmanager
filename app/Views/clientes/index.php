<?= $this->extend('templates/header') ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2"><?= $title ?></h1>
        <a href="<?= base_url('clientes/new') ?>" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Nuevo Cliente
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Identificación</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Ciudad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($clientes)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No hay clientes registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($clientes as $cliente): ?>
                                <tr>
                                    <td><?= $cliente['tipo_identificacion'] . ': ' . $cliente['identificacion'] ?></td>
                                    <td>
                                        <?= $cliente['nombre'] . ' ' . $cliente['apellido'] ?>
                                        <?php if (!empty($cliente['empresa'])): ?>
                                            <br><small class="text-muted"><?= $cliente['empresa'] ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $cliente['telefono'] ?? 'N/A' ?></td>
                                    <td><?= $cliente['email'] ?? 'N/A' ?></td>
                                    <td><?= $cliente['ciudad'] ?? 'N/A' ?></td>
                                    <td>
                                        <a href="<?= base_url('clientes/view/' . $cliente['id']) ?>" class="btn btn-sm btn-info" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('clientes/edit/' . $cliente['id']) ?>" class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $cliente['id'] ?>" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>

                                        <!-- Modal de confirmación para eliminar -->
                                        <div class="modal fade" id="deleteModal<?= $cliente['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Está seguro de que desea eliminar al cliente "<?= $cliente['nombre'] . ' ' . $cliente['apellido'] ?>"?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <a href="<?= base_url('clientes/delete/' . $cliente['id']) ?>" class="btn btn-danger">Eliminar</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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