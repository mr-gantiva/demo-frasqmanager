<?= $this->extend('auth/layout') ?>

<?= $this->section('title') ?>Iniciar Sesión<?= $this->endSection() ?>

<?= $this->section('main') ?>

<?php if (session()->has('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->has('message')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form action="<?= base_url('login/auth') ?>" method="post">
    <?= csrf_field() ?>

    <div class="mb-4">
        <label for="email" class="form-label">Correo Electrónico</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            <input type="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>"
                id="email" name="email" value="<?= old('email') ?>" placeholder="ejemplo@empresa.com" required autofocus>
            <?php if (session('errors.email')): ?>
                <div class="invalid-feedback"><?= session('errors.email') ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-4">
        <label for="password" class="form-label">Contraseña</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input type="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>"
                id="password" name="password" placeholder="Contraseña" required>
            <?php if (session('errors.password')): ?>
                <div class="invalid-feedback"><?= session('errors.password') ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-4 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
        <label class="form-check-label" for="remember">Recordarme</label>
    </div>

    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
        </button>
    </div>
</form>

<?= $this->endSection() ?>