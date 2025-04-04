<?= $this->extend('templates/header') ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2"><?= $title ?></h1>
        <div>
            <a href="<?= base_url('clientes/edit/' . $cliente['id']) ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="<?= base_url('clientes') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <?= $cliente['nombre'] . ' ' . $cliente['apellido'] ?>
                <?php if (!empty($cliente['empresa'])): ?>
                    <small class="text-muted d-block"><?= $cliente['empresa'] ?></small>
                <?php endif; ?>
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="h5 mb-3">Información Personal</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th class="bg-light w-25">Identificación</th>
                            <td><?= $cliente['tipo_identificacion'] . ': ' . $cliente['identificacion'] ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Nombre Completo</th>
                            <td><?= $cliente['nombre'] . ' ' . $cliente['apellido'] ?></td>
                        </tr>
                        <?php if (!empty($cliente['empresa'])): ?>
                            <tr>
                                <th class="bg-light">Empresa</th>
                                <td><?= $cliente['empresa'] ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>

                <div class="col-md-6">
                    <h4 class="h5 mb-3">Información de Contacto</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th class="bg-light w-25">Teléfono</th>
                            <td><?= $cliente['telefono'] ?? 'No registrado' ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Email</th>
                            <td><?= $cliente['email'] ?? 'No registrado' ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Ciudad</th>
                            <td><?= $cliente['ciudad'] ?? 'No registrada' ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Dirección</th>
                            <td><?= $cliente['direccion'] ?? 'No registrada' ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <h4 class="h5 mb-3">Historial de Ventas</h4>
                <p class="text-muted">No hay ventas registradas para este cliente</p>
                <!-- Aquí se mostrará el historial de ventas en la fase 4 -->
            </div>
        </div>
        <div class="card-footer text-end">
            <small class="text-muted">Cliente registrado el: <?= date('d/m/Y H:i', strtotime($cliente['created_at'])) ?></small>
        </div>
    </div>
</div>

<?= $this->include('templates/footer') ?>