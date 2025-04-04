<?= $this->extend('templates/header') ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2"><?= $title ?></h1>
        <a href="<?= base_url('ventas') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <form action="<?= base_url('ventas/create') ?>" method="post" id="ventaForm">
        <?= csrf_field() ?>

        <div class="row">

            <!-- Productos -->
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Productos</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <select class="form-select" id="producto_selector">
                                        <option value="">Seleccione un producto</option>
                                        <?php foreach ($productos as $producto): ?>
                                            <option value="<?= $producto['id'] ?>"
                                                data-nombre="<?= $producto['nombre'] ?>"
                                                data-precio="<?= $producto['precio_venta'] ?>"
                                                data-stock="<?= $producto['stock'] ?>">
                                                <?= $producto['codigo'] . ' - ' . $producto['nombre'] . ' ($' . number_format($producto['precio_venta'], 2) . ')' ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-success w-100" id="agregarProducto">
                                        <i class="fas fa-plus"></i> Agregar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tablaProductos">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40%">Producto</th>
                                        <th style="width: 15%">Precio</th>
                                        <th style="width: 15%">Cantidad</th>
                                        <th style="width: 20%">Subtotal</th>
                                        <th style="width: 10%">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="filaVacia">
                                        <td colspan="5" class="text-center text-muted">Agregue productos a la venta</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th id="totalVenta" class="text-end">$0.00</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Campo oculto para el total -->
                        <input type="hidden" name="total" id="totalInput" value="0">
                    </div>
                </div>
            </div>

            <!-- Información de la venta -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Información de la Venta</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="codigo" class="form-label">Código de Venta*</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" value="<?= $codigo_venta ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha*</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" value="<?= $fecha ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="cliente_id" class="form-label">Cliente*</label>
                            <select class="form-select" id="cliente_id" name="cliente_id" required>
                                <option value="">Seleccione un cliente</option>
                                <?php foreach ($clientes as $cliente): ?>
                                    <option value="<?= $cliente['id'] ?>"><?= $cliente['nombre'] . ' ' . $cliente['apellido'] . ' - ' . $cliente['identificacion'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="canal_venta_id" class="form-label">Canal de Venta</label>
                            <select class="form-select" id="canal_venta_id" name="canal_venta_id">
                                <option value="">Seleccione un canal</option>
                                <?php foreach ($canales_venta as $id => $nombre): ?>
                                    <option value="<?= $id ?>" <?= old('canal_venta_id') == $id ? 'selected' : '' ?>>
                                        <?= $nombre ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="notas" class="form-label">Notas</label>
                            <textarea class="form-control" id="notas" name="notas" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Información Financiera</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="estado_venta" class="form-label">Estado de la Venta*</label>
                                <select class="form-select" id="estado_venta" name="estado_venta" required>
                                    <?php foreach ($estados_venta as $key => $value): ?>
                                        <option value="<?= $key ?>" <?= old('estado_venta') == $key ? 'selected' : '' ?>>
                                            <?= $value ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="estado_pago" class="form-label">Estado de Pago*</label>
                                <select class="form-select" id="estado_pago" name="estado_pago" required>
                                    <?php foreach ($estados_pago as $key => $value): ?>
                                        <option value="<?= $key ?>" <?= old('estado_pago') == $key ? 'selected' : '' ?>>
                                            <?= $value ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="banco" class="form-label">Banco*</label>
                                <select class="form-select" id="banco" name="banco" required>
                                    <?php foreach ($bancos as $key => $value): ?>
                                        <option value="<?= $key ?>" <?= old('banco') == $key ? 'selected' : '' ?>>
                                            <?= $value ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div id="alerta-banco" class="text-danger mt-1 d-none">
                                    <small><i class="fas fa-exclamation-triangle"></i> Recuerde actualizar el estado del pago en menos de 24 horas</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="comision" class="form-label">Comisión* ($)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="comision" name="comision" value="<?= old('comision', '0.00') ?>" required>
                            </div>

                            <div class="col-md-4">
                                <label for="comision_fija" class="form-label">Comisión Fija* ($)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="comision_fija" name="comision_fija" value="<?= old('comision_fija', '0.00') ?>" required>
                            </div>

                            <div class="col-md-4">
                                <label for="valor_envio" class="form-label">Valor del Envío* ($)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="valor_envio" name="valor_envio" value="<?= old('valor_envio', '0.00') ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="tipo_envio" class="form-label">Tipo de Envío*</label>
                                <select class="form-select" id="tipo_envio" name="tipo_envio" required>
                                    <?php foreach ($tipos_envio as $key => $value): ?>
                                        <option value="<?= $key ?>" <?= old('tipo_envio') == $key ? 'selected' : '' ?>>
                                            <?= $value ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="tipo_publicacion" class="form-label">Tipo de Publicación*</label>
                                <select class="form-select" id="tipo_publicacion" name="tipo_publicacion" required>
                                    <?php foreach ($tipos_publicacion as $key => $value): ?>
                                        <option value="<?= $key ?>" <?= old('tipo_publicacion') == $key ? 'selected' : '' ?>>
                                            <?= $value ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="reteiva" class="form-label">Reteiva 15%* ($)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="reteiva" name="reteiva" value="<?= old('reteiva', '0.00') ?>" required>
                            </div>

                            <div class="col-md-3">
                                <label for="factura_electronica" class="form-label">Factura Electrónica</label>
                                <input type="text" class="form-control" id="factura_electronica" name="factura_electronica" value="<?= old('factura_electronica') ?>">
                                <div class="text-muted mt-1">
                                    <small><i class="fas fa-info-circle"></i> Recuerde generar la factura electrónica</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="wompi_comision" class="form-label">Wompi Comisión* ($)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="wompi_comision" name="wompi_comision" value="<?= old('wompi_comision', '0.00') ?>" required>
                            </div>

                            <div class="col-md-4">
                                <label for="wompi_iva" class="form-label">Wompi IVA* ($)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="wompi_iva" name="wompi_iva" value="<?= old('wompi_iva', '0.00') ?>" required>
                            </div>

                            <div class="col-md-4">
                                <label for="valor_real_preview" class="form-label">Valor Real Estimado ($)</label>
                                <input type="text" class="form-control bg-light" id="valor_real_preview" readonly>
                                <small class="text-muted">Este valor se calculará automáticamente</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-secondary me-md-2" id="resetForm">
                        <i class="fas fa-undo"></i> Restablecer
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Registrar Venta
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let contador = 0;
        const tablaProductos = document.getElementById('tablaProductos');
        const filaVacia = document.getElementById('filaVacia');
        const totalVenta = document.getElementById('totalVenta');
        const totalInput = document.getElementById('totalInput');
        const submitBtn = document.getElementById('submitBtn');
        const resetForm = document.getElementById('resetForm');

        // Función para actualizar el total
        function actualizarTotal() {
            let total = 0;
            const subtotales = document.querySelectorAll('.subtotal-producto');

            subtotales.forEach(function(subtotal) {
                total += parseFloat(subtotal.value || 0);
            });

            totalVenta.textContent = '$' + total.toFixed(2);
            totalInput.value = total.toFixed(2);

            // Mostrar/ocultar fila vacía
            if (document.querySelectorAll('tr.fila-producto').length > 0) {
                filaVacia.style.display = 'none';
            } else {
                filaVacia.style.display = '';
            }

            // Habilitar/deshabilitar botón de envío
            submitBtn.disabled = total <= 0;
        }

        // Agregar producto
        document.getElementById('agregarProducto').addEventListener('click', function() {
            const selector = document.getElementById('producto_selector');
            const productoId = selector.value;

            if (!productoId) {
                alert('Por favor, seleccione un producto');
                return;
            }

            // Verificar si el producto ya está en la tabla
            if (document.querySelector(`input[name="producto_id[]"][value="${productoId}"]`)) {
                alert('Este producto ya está agregado. Modifique la cantidad si desea.');
                return;
            }

            const option = selector.options[selector.selectedIndex];
            const nombre = option.dataset.nombre;
            const precio = parseFloat(option.dataset.precio);
            const stock = parseInt(option.dataset.stock);

            // Crear fila
            const fila = document.createElement('tr');
            fila.classList.add('fila-producto');
            fila.innerHTML = `
            <td>
                ${nombre}
                <input type="hidden" name="producto_id[]" value="${productoId}">
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01" min="0.01" class="form-control precio-producto" name="precio[]" value="${precio.toFixed(2)}" required>
                </div>
            </td>
            <td>
                <input type="number" min="1" max="${stock}" class="form-control cantidad-producto" name="cantidad[]" value="1" required>
                <small class="text-muted">Disponible: ${stock}</small>
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01" class="form-control subtotal-producto" name="subtotal[]" value="${precio.toFixed(2)}" readonly>
                </div>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm eliminar-producto">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

            // Eventos para calcular subtotal
            const precioInput = fila.querySelector('.precio-producto');
            const cantidadInput = fila.querySelector('.cantidad-producto');
            const subtotalInput = fila.querySelector('.subtotal-producto');

            function calcularSubtotal() {
                const precio = parseFloat(precioInput.value || 0);
                const cantidad = parseInt(cantidadInput.value || 0);
                const subtotal = precio * cantidad;
                subtotalInput.value = subtotal.toFixed(2);
                actualizarTotal();
            }

            precioInput.addEventListener('change', calcularSubtotal);
            cantidadInput.addEventListener('change', function() {
                if (parseInt(this.value) > stock) {
                    alert(`No hay suficiente stock. Máximo disponible: ${stock}`);
                    this.value = stock;
                }
                if (parseInt(this.value) < 1) {
                    this.value = 1;
                }
                calcularSubtotal();
            });

            // Evento para eliminar producto
            fila.querySelector('.eliminar-producto').addEventListener('click', function() {
                fila.remove();
                actualizarTotal();
            });

            // Agregar fila a la tabla
            tablaProductos.querySelector('tbody').appendChild(fila);

            // Actualizar total
            actualizarTotal();

            // Limpiar selector
            selector.value = '';
        });

        // Restablecer formulario
        resetForm.addEventListener('click', function() {
            document.querySelectorAll('.fila-producto').forEach(function(fila) {
                fila.remove();
            });
            actualizarTotal();
        });

        // Validar formulario antes de enviar
        document.getElementById('ventaForm').addEventListener('submit', function(e) {
            if (document.querySelectorAll('tr.fila-producto').length === 0) {
                e.preventDefault();
                alert('Debe agregar al menos un producto a la venta');
            }
        });
    });
</script>

<script>
    // Función para calcular el valor real
    function calcularValorReal() {
        const total = parseFloat(document.getElementById('totalInput').value || 0);
        const comision = parseFloat(document.getElementById('comision').value || 0);
        const comisionFija = parseFloat(document.getElementById('comision_fija').value || 0);
        const reteiva = parseFloat(document.getElementById('reteiva').value || 0);
        const wompiComision = parseFloat(document.getElementById('wompi_comision').value || 0);
        const wompiIva = parseFloat(document.getElementById('wompi_iva').value || 0);

        const valorReal = total - (comision + comisionFija + reteiva + wompiComision + wompiIva);
        document.getElementById('valor_real_preview').value = '$' + (valorReal > 0 ? valorReal.toFixed(2) : '0.00');
    }

    // Función para mostrar/ocultar alerta de banco
    function manejarAlertaBanco() {
        const banco = document.getElementById('banco').value;
        const alertaBanco = document.getElementById('alerta-banco');

        if (banco === 'Pendiente consignar') {
            alertaBanco.classList.remove('d-none');
        } else {
            alertaBanco.classList.add('d-none');
        }
    }

    // Escuchar cambios en los campos relevantes para recalcular el valor real
    document.addEventListener('DOMContentLoaded', function() {
        const camposCalculo = [
            'totalInput', 'comision', 'comision_fija', 'reteiva',
            'wompi_comision', 'wompi_iva'
        ];

        camposCalculo.forEach(id => {
            document.getElementById(id).addEventListener('change', calcularValorReal);
            document.getElementById(id).addEventListener('keyup', calcularValorReal);
        });

        // Para el campo total, que se actualiza mediante JavaScript al agregar productos
        const observer = new MutationObserver(calcularValorReal);
        observer.observe(document.getElementById('totalVenta'), {
            characterData: true,
            childList: true,
            subtree: true
        });

        // Manejar la alerta del banco
        document.getElementById('banco').addEventListener('change', manejarAlertaBanco);

        // Inicializar
        calcularValorReal();
        manejarAlertaBanco();
    });
</script>

<?= $this->include('templates/footer') ?>