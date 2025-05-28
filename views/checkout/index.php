<?php require_once 'views/templates/header.php'; ?>

<div class="container py-5">
    <h1 class="mb-4">Finalizar Compra</h1>

    <?php if (SessionHelper::hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?php echo SessionHelper::getFlash('error'); ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Formulario de datos -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Datos de Envío</h5>
                    <form action="<?php echo BASE_URL; ?>checkout/procesar" method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo $usuario['nombre'] ?? ''; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" 
                                       value="<?php echo $usuario['apellidos'] ?? ''; ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo $usuario['email'] ?? ''; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" 
                                       value="<?php echo $usuario['telefono'] ?? ''; ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                            </div>
                            <div class="col-md-6">
                                <label for="codigo_postal" class="form-label">Código Postal</label>
                                <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Método de Pago</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metodo_pago" 
                                       id="tarjeta" value="tarjeta" required>
                                <label class="form-check-label" for="tarjeta">
                                    Tarjeta de Crédito/Débito
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metodo_pago" 
                                       id="transferencia" value="transferencia" required>
                                <label class="form-check-label" for="transferencia">
                                    Transferencia Bancaria
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metodo_pago" 
                                       id="efectivo" value="efectivo" required>
                                <label class="form-check-label" for="efectivo">
                                    Pago en Efectivo (Contrareembolso)
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Confirmar Pedido</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Resumen del pedido -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Resumen del Pedido</h5>
                    
                    <?php foreach ($items as $item): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span><?php echo $item['cantidad']; ?>x <?php echo $item['nombre']; ?></span>
                            <span>
                                <?php if ($item['precio_final'] < $item['precio']): ?>
                                    <span class="text-danger fw-bold">€<?php echo number_format($item['precio_final'], 2); ?></span>
                                    <span class="text-muted text-decoration-line-through ms-1">€<?php echo number_format($item['precio'], 2); ?></span>
                                <?php else: ?>
                                    €<?php echo number_format($item['precio'], 2); ?>
                                <?php endif; ?>
                                x<?php echo $item['cantidad']; ?> = €<?php echo number_format($item['precio_final'] * $item['cantidad'], 2); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>€<?php echo number_format($subtotal, 2); ?></span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>IVA (21%)</span>
                        <span>€<?php echo number_format($iva, 2); ?></span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <strong>Total</strong>
                        <strong>€<?php echo number_format($total, 2); ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/templates/footer.php'; ?> 