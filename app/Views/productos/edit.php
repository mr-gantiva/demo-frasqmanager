<?= $this->extend('templates/header') ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2"><?= $title ?></h1>
        <a href="<?= base_url('productos') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('productos/update/' . $producto['id']) ?>" method="post">
                <?= csrf_field() ?>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="codigo" class="form-label">Código*</label>
                        <input type="text" class="form-control"
                            id="codigo" name="codigo" value="<?= $producto['codigo'] ?>" readonly>
                        <small class="text-muted">El código no puede ser modificado</small>
                    </div>

                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre*</label>
                        <input type="text" class="form-control <?= session('errors.nombre') ? 'is-invalid' : '' ?>"
                            id="nombre" name="nombre" value="<?= old('nombre', $producto['nombre']) ?>" required>
                        <?php if (session('errors.nombre')): ?>
                            <div class="invalid-feedback"><?= session('errors.nombre') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?= old('descripcion', $producto['descripcion']) ?></textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="precio_compra" class="form-label">Precio de Compra*</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control <?= session('errors.precio_compra') ? 'is-invalid' : '' ?>"
                                id="precio_compra" name="precio_compra" value="<?= old('precio_compra', $producto['precio_compra']) ?>" required>
                            <?php if (session('errors.precio_compra')): ?>
                                <div class="invalid-feedback"><?= session('errors.precio_compra') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="precio_venta" class="form-label">Precio de Venta*</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control <?= session('errors.precio_venta') ? 'is-invalid' : '' ?>"
                                id="precio_venta" name="precio_venta" value="<?= old('precio_venta', $producto['precio_venta']) ?>" required>
                            <?php if (session('errors.precio_venta')): ?>
                                <div class="invalid-feedback"><?= session('errors.precio_venta') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="stock" class="form-label">Stock*</label>
                        <input type="number" class="form-control <?= session('errors.stock') ? 'is-invalid' : '' ?>"
                            id="stock" name="stock" value="<?= old('stock', $producto['stock']) ?>" required>
                        <?php if (session('errors.stock')): ?>
                            <div class="invalid-feedback"><?= session('errors.stock') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <input type="text" class="form-control" id="categoria" name="categoria" value="<?= old('categoria', $producto['categoria']) ?>">
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-secondary me-md-2">
                        <i class="fas fa-undo"></i> Restablecer
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Actualizar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('templates/footer') ?>