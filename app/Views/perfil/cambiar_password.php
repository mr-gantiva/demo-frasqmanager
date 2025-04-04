<?= $this->extend('templates/header') ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2"><?= $title ?></h1>
        <a href="<?= base_url('perfil') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Cambiar Contraseña</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('perfil/cambiarPassword') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Contraseña Actual</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control <?= session('errors.current_password') ? 'is-invalid' : '' ?>"
                                    id="current_password" name="current_password" required>
                                <?php if (session('errors.current_password')): ?>
                                    <div class="invalid-feedback"><?= session('errors.current_password') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nueva Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="password" class="form-control <?= session('errors.new_password') ? 'is-invalid' : '' ?>"
                                    id="new_password" name="new_password" required>
                                <?php if (session('errors.new_password')): ?>
                                    <div class="invalid-feedback"><?= session('errors.new_password') ?></div>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted">La contraseña debe tener al menos 8 caracteres e incluir mayúsculas, minúsculas y números</small>
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="password" class="form-control <?= session('errors.confirm_password') ? 'is-invalid' : '' ?>"
                                    id="confirm_password" name="confirm_password" required>
                                <?php if (session('errors.confirm_password')): ?>
                                    <div class="invalid-feedback"><?= session('errors.confirm_password') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Actualizar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('templates/footer') ?>