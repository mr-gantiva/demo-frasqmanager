<?= $this->extend('templates/header') ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2"><?= $title ?></h1>
        <a href="<?= base_url('clientes') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('clientes/update/' . $cliente['id']) ?>" method="post">
                <?= csrf_field() ?>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tipo_identificacion" class="form-label">Tipo de Identificación*</label>
                        <select class="form-select <?= session('errors.tipo_identificacion') ? 'is-invalid' : '' ?>"
                            id="tipo_identificacion" name="tipo_identificacion" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($tipos_identificacion as $key => $value): ?>
                                <option value="<?= $key ?>" <?= old('tipo_identificacion', $cliente['tipo_identificacion']) == $key ? 'selected' : '' ?>>
                                    <?= $value ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (session('errors.tipo_identificacion')): ?>
                            <div class="invalid-feedback"><?= session('errors.tipo_identificacion') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label for="identificacion" class="form-label">Número de Identificación*</label>
                        <input type="text" class="form-control <?= session('errors.identificacion') ? 'is-invalid' : '' ?>"
                            id="identificacion" name="identificacion" value="<?= old('identificacion', $cliente['identificacion']) ?>" required>
                        <?php if (session('errors.identificacion')): ?>
                            <div class="invalid-feedback"><?= session('errors.identificacion') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre*</label>
                        <input type="text" class="form-control <?= session('errors.nombre') ? 'is-invalid' : '' ?>"
                            id="nombre" name="nombre" value="<?= old('nombre', $cliente['nombre']) ?>" required>
                        <?php if (session('errors.nombre')): ?>
                            <div class="invalid-feedback"><?= session('errors.nombre') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido"
                            value="<?= old('apellido', $cliente['apellido']) ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="empresa" class="form-label">Empresa</label>
                    <input type="text" class="form-control" id="empresa" name="empresa"
                        value="<?= old('empresa', $cliente['empresa']) ?>">
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control <?= session('errors.telefono') ? 'is-invalid' : '' ?>"
                            id="telefono" name="telefono" value="<?= old('telefono', $cliente['telefono']) ?>">
                        <?php if (session('errors.telefono')): ?>
                            <div class="invalid-feedback"><?= session('errors.telefono') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>"
                            id="email" name="email" value="<?= old('email', $cliente['email']) ?>">
                        <?php if (session('errors.email')): ?>
                            <div class="invalid-feedback"><?= session('errors.email') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="ciudad" class="form-label">Ciudad</label>
                        <input type="text" class="form-control" id="ciudad" name="ciudad"
                            value="<?= old('ciudad', $cliente['ciudad']) ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion"
                            value="<?= old('direccion', $cliente['direccion']) ?>">
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-secondary me-md-2">
                        <i class="fas fa-undo"></i> Restablecer
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Actualizar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('templates/footer') ?>