<?php require_once 'views/templates/header.php'; ?>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container py-5">
    <h1 class="mb-4">Mi Carrito</h1>

    <?php if (SessionHelper::hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo SessionHelper::getFlash('success'); ?>
        </div>
    <?php endif; ?>

    <?php if (SessionHelper::hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?php echo SessionHelper::getFlash('error'); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($carrito['items'])): ?>
        <div class="alert alert-info">
            Tu carrito está vacío. <a href="<?php echo BASE_URL; ?>productos" class="alert-link">Ver productos</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carrito['items'] as $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php
                                    $img = $item['imagen_url'];
                                    if (empty($img)) {
                                        $img = BASE_URL . 'assets/img/no-image.jpg';
                                    } elseif (strpos($img, 'http') !== 0) {
                                        $img = BASE_URL . ltrim($img, '/');
                                    }
                                    ?>
                                    <img src="<?php echo htmlspecialchars($img); ?>"
                                         alt="<?php echo htmlspecialchars($item['nombre']); ?>"
                                         class="img-thumbnail me-3"
                                         style="width: 50px;">
                                    <div>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($item['nombre']); ?></h6>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($item['precio_final'] < $item['precio']): ?>
                                    <span class="text-danger fw-bold">€<?php echo number_format($item['precio_final'], 2); ?></span>
                                    <span class="text-muted text-decoration-line-through ms-1">€<?php echo number_format($item['precio'], 2); ?></span>
                                <?php else: ?>
                                    €<?php echo number_format($item['precio'], 2); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="input-group" style="width: 130px;">
                                    <button class="btn btn-outline-secondary btn-sm" 
                                            onclick="actualizarCantidad(<?php echo $item['producto_id']; ?>, -1)">
                                        -
                                    </button>
                                    <input type="number" 
                                           class="form-control form-control-sm text-center" 
                                           value="<?php echo $item['cantidad']; ?>"
                                           min="1"
                                           max="<?php echo $item['stock']; ?>"
                                           onchange="actualizarCantidadDirecta(<?php echo $item['producto_id']; ?>, this.value)">
                                    <button class="btn btn-outline-secondary btn-sm" 
                                            onclick="actualizarCantidad(<?php echo $item['producto_id']; ?>, 1)">
                                        +
                                    </button>
                                </div>
                            </td>
                            <td>€<?php echo number_format($item['precio_final'] * $item['cantidad'], 2); ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm" 
                                        onclick="eliminarProducto(<?php echo $item['producto_id']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td><strong>€<?php echo number_format($carrito['total'], 2); ?></strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button class="btn btn-outline-danger" onclick="vaciarCarrito()">
                Vaciar Carrito
            </button>
            <a href="<?php echo BASE_URL; ?>checkout" class="btn btn-primary">
                Proceder al Pago
            </a>
        </div>

        <script>
            function actualizarCantidad(productoId, cambio) {
                const input = document.querySelector(`input[onchange*="${productoId}"]`);
                const nuevaCantidad = parseInt(input.value) + cambio;
                if (nuevaCantidad >= 1 && nuevaCantidad <= parseInt(input.max)) {
                    actualizarCantidadDirecta(productoId, nuevaCantidad);
                }
            }

            function actualizarCantidadDirecta(productoId, cantidad) {
                fetch('<?php echo BASE_URL; ?>carrito/actualizar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `producto_id=${productoId}&cantidad=${cantidad}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }

            function eliminarProducto(productoId) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¿Deseas eliminar este producto del carrito?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'btn btn-danger me-2',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('<?php echo BASE_URL; ?>carrito/eliminar', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: `producto_id=${productoId}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        });
                    }
                });
            }

            function vaciarCarrito() {
                Swal.fire({
                    title: '¿Vaciar carrito?',
                    text: '¿Estás seguro de que deseas vaciar el carrito?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, vaciar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'btn btn-danger me-2',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?php echo BASE_URL; ?>carrito/vaciar';
                    }
                });
            }
        </script>
    <?php endif; ?>
</div>

<?php require_once 'views/templates/footer.php'; ?> 