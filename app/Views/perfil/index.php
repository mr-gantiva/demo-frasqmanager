<?= $this->extend('templates/header') ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2"><?= $title ?></h1>
        <div>
            <a href="<?= base_url('perfil/actualizar') ?>" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Editar Perfil
            </a>
            <a href="<?= base_url('perfil/cambiarPassword') ?>" class="btn btn-warning">
                <i class="fas fa-key"></i> Cambiar Contraseña
            </a>
        </div>
    </div>

    <?php if (session()->has('message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('message') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Información de Usuario</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4 d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-xl bg-secondary rounded-circle text-white p-4 me-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4><?= $user->username ?></h4>
                            <p class="text-muted mb-0">
                                <?php if ($user->inGroup('admin')): ?>
                                    <span class="badge bg-primary">Administrador</span>
                                <?php elseif ($user->inGroup('vendedor')): ?>
                                    <span class="badge bg-info">Vendedor</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Usuario</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th class="bg-light" style="width: 30%">Nombre de Usuario</th>
                                <td><?= $user->username ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Correo Electrónico</th>
                                <td><?= $user->email ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Rol</th>
                                <td>
                                    <?php foreach ($user->getGroups() as $group): ?>
                                        <span class="badge bg-primary"><?= ucfirst($group) ?></span>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Estado</th>
                                <td>
                                    <?php if ($user->active): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Última Actualización</th>
                                <td>
                                    <?php if (isset($user->updated_at)): ?>
                                        <?= date('d/m/Y H:i', strtotime($user->updated_at)) ?>
                                    <?php else: ?>
                                        No disponible
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('perfil/actualizar') ?>" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i> Editar Perfil
                        </a>
                        <a href="<?= base_url('perfil/cambiarPassword') ?>" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i> Cambiar Contraseña
                        </a>
                        <a href="<?= base_url('/') ?>" class="btn btn-secondary">
                            <i class="fas fa-tachometer-alt me-2"></i> Ir al Dashboard
                        </a>
                        <a href="<?= base_url('logout') ?>" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('templates/footer') ?>