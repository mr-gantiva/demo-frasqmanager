<?= $this->extend('templates/header') ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2"><?= $title ?></h1>
        <a href="<?= base_url('usuarios') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('usuarios/create') ?>" method="post">
                <?= csrf_field() ?>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="username" class="form-label">Nombre de Usuario*</label>
                        <input type="text" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>"
                            id="username" name="username" value="<?= old('username') ?>" required>
                        <?php if (session('errors.username')): ?>
                            <div class="invalid-feedback"><?= session('errors.username') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email*</label>
                        <input type="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>"
                            id="email" name="email" value="<?= old('email') ?>" required>
                        <?php if (session('errors.email')): ?>
                            <div class="invalid-feedback"><?= session('errors.email') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Contraseña*</label>
                        <input type="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>"
                            id="password" name="password" required>
                        <?php if (session('errors.password')): ?>
                            <div class="invalid-feedback"><?= session('errors.password') ?></div>
                        <?php else: ?>
                            <small class="text-muted">Mínimo 8 caracteres, debe incluir mayúsculas, minúsculas y números</small>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label for="grupo" class="form-label">Rol*</label>
                        <select class="form-select <?= session('errors.grupo') ? 'is-invalid' : '' ?>"
                            id="grupo" name="grupo" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($grupos as $value => $label): ?>
                                <option value="<?= $value ?>" <?= old('grupo') == $value ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (session('errors.grupo')): ?>
                            <div class="invalid-feedback"><?= session('errors.grupo') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-secondary me-md-2">
                        <i class="fas fa-undo"></i> Restablecer
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('templates/footer') ?>