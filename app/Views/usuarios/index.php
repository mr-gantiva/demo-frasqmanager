<?= $this->extend('templates/header') ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2"><?= $title ?></h1>
        <a href="<?= base_url('usuarios/new') ?>" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Nuevo Usuario
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($usuarios)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay usuarios registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?= $usuario->username ?></td>
                                    <td><?= $usuario->email ?></td>
                                    <td>
                                        <?php foreach ($usuario->getGroups() as $group): ?>
                                            <span class="badge bg-info"><?= ucfirst($group) ?></span>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <?php if ($usuario->active): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($usuario->id != auth()->id()): ?>
                                            <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $usuario->id ?>">
                                                <i class="fas fa-trash"></i>
                                            </a>

                                            <!-- Modal de confirmación para eliminar -->
                                            <div class="modal fade" id="deleteModal<?= $usuario->id ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Confirmar eliminación</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>¿Está seguro de que desea eliminar al usuario "<?= $usuario->username ?>"?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <a href="<?= base_url('usuarios/delete/' . $usuario->id) ?>" class="btn btn-danger">Eliminar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted"><i class="fas fa-user-shield"></i> Usuario actual</span>
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