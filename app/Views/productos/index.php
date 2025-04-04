<?= $this->extend('templates/header') ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2"><?= $title ?></h1>
        <a href="<?= base_url('productos/new') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Producto
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Precio Compra</th>
                            <th>Precio Venta</th>
                            <th>Stock</th>
                            <th>Categoría</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($productos)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay productos registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($productos as $producto): ?>
                                <tr>
                                    <td><?= $producto['codigo'] ?></td>
                                    <td><?= $producto['nombre'] ?></td>
                                    <td>$<?= number_format($producto['precio_compra'], 2, '.', ',') ?></td>
                                    <td>$<?= number_format($producto['precio_venta'], 2, '.', ',') ?></td>
                                    <td><?= $producto['stock'] ?></td>
                                    <td><?= $producto['categoria'] ?? 'N/A' ?></td>
                                    <td>
                                        <a href="<?= base_url('productos/edit/' . $producto['id']) ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $producto['id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </a>

                                        <!-- Modal de confirmación para eliminar -->
                                        <div class="modal fade" id="deleteModal<?= $producto['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Está seguro de que desea eliminar el producto "<?= $producto['nombre'] ?>"?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <a href="<?= base_url('productos/delete/' . $producto['id']) ?>" class="btn btn-danger">Eliminar</a>
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