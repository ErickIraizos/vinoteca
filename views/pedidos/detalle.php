<?php require_once 'views/templates/header.php'; ?>

<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Detalle del Pedido #<?php echo $pedido['pedido_id']; ?></h1>
            
            <!-- Información general del pedido -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Información del Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?></p>
                            <p><strong>Estado:</strong> 
                                <span class="badge <?php 
                                    echo $pedido['estado'] == 'pendiente' ? 'bg-warning' : 
                                        ($pedido['estado'] == 'completado' ? 'bg-success' : 
                                        ($pedido['estado'] == 'cancelado' ? 'bg-danger' : 'bg-info')); 
                                ?>">
                                    <?php echo ucfirst($pedido['estado']); ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total:</strong> €<?php echo number_format($pedido['total'], 2); ?></p>
                            <p><strong>Método de Pago:</strong> <?php echo $pedido['metodo_pago']; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalles de los productos -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Productos del Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($pedido['detalles'])): ?>
                                    <?php foreach ($pedido['detalles'] as $detalle): ?>
                                        <tr>
                                            <td><?php echo $detalle['detalle_id']; ?></td>
                                            <td><?php echo $detalle['nombre_producto']; ?></td>
                                            <td><?php echo $detalle['cantidad']; ?></td>
                                            <td>€<?php echo number_format($detalle['precio_unitario'], 2); ?></td>
                                            <td>€<?php echo number_format($detalle['subtotal'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No hay detalles disponibles para este pedido.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                    <td>€<?php echo number_format($pedido['total'], 2); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="mt-4 d-flex flex-column flex-md-row justify-content-center gap-3">
                <a href="<?php echo BASE_URL; ?>pedidos" class="btn btn-secondary btn-lg px-4">
                    <i class="fas fa-arrow-left me-2"></i>Volver a Pedidos
                </a>
                <?php if ($pedido['estado'] === 'pendiente'): ?>
                    <button type="button" 
                           class="btn btn-danger btn-lg px-4"
                           onclick="confirmarCancelacion(<?php echo $pedido['pedido_id']; ?>)">
                        <i class="fas fa-times me-2"></i>Cancelar Pedido
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarCancelacion(pedidoId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas cancelar este pedido? Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, cancelar pedido',
        cancelButtonText: 'No, mantener pedido',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?php echo BASE_URL; ?>pedidos/cancelar/' + pedidoId;
        }
    });
}
</script>

<?php require_once 'views/templates/footer.php'; ?> 