<?php require_once 'views/templates/header.php'; ?>

<div class="container py-5">
    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Pedidos</h5>
                    <h2 class="mb-0"><?php echo $estadisticas['total_pedidos']; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Pedidos Pendientes</h5>
                    <h2 class="mb-0"><?php echo $estadisticas['pedidos_pendientes']; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Pedidos Completados</h5>
                    <h2 class="mb-0"><?php echo $estadisticas['pedidos_completados']; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Ventas</h5>
                    <h2 class="mb-0">€<?php echo number_format($estadisticas['total_ventas'], 2); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="pendiente" <?php echo $filtros['estado'] === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="procesando" <?php echo $filtros['estado'] === 'procesando' ? 'selected' : ''; ?>>Procesando</option>
                        <option value="enviado" <?php echo $filtros['estado'] === 'enviado' ? 'selected' : ''; ?>>Enviado</option>
                        <option value="entregado" <?php echo $filtros['estado'] === 'entregado' ? 'selected' : ''; ?>>Entregado</option>
                        <option value="cancelado" <?php echo $filtros['estado'] === 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo $filtros['fecha_inicio']; ?>">
                </div>
                <div class="col-md-3">
                    <label for="fecha_fin" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $filtros['fecha_fin']; ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                    <a href="<?php echo BASE_URL; ?>pedidos" class="btn btn-secondary">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Pedidos -->
    <div class="card">
        <div class="card-header bg-white">
            <h4 class="mb-0">Lista de Pedidos</h4>
        </div>
        <div class="card-body">
            
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID Pedido</th>
                                <th>Usuario ID</th>
                                <th>Fecha Pedido</th>
                                <th>Estado</th>
                                <th>Total</th>
                                <th>Dirección Envío</th>
                                <th>Método Pago</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedidos as $pedido): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($pedido['pedido_id']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['usuario_id']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?></td>
                                    <td>
                                        <?php
                                        $estado_class = [
                                            'pendiente' => 'warning',
                                            'procesando' => 'info',
                                            'enviado' => 'primary',
                                            'entregado' => 'success',
                                            'cancelado' => 'danger'
                                        ];
                                        $clase = $estado_class[$pedido['estado']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $clase; ?>">
                                            <?php echo ucfirst(htmlspecialchars($pedido['estado'])); ?>
                                        </span>
                                    </td>
                                    <td>€<?php echo number_format($pedido['total'], 2); ?></td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo nl2br(htmlspecialchars($pedido['direccion_envio'])); ?>
                                        </small>
                                    </td>
                                    <td><?php echo ucfirst(htmlspecialchars($pedido['metodo_pago'])); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?php echo BASE_URL; ?>pedidos/detalle/<?php echo $pedido['pedido_id']; ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye me-1"></i>Ver
                                            </a>
                                            <?php if ($pedido['estado'] === 'pendiente'): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger ms-1"
                                                        onclick="confirmarCancelacion(<?php echo $pedido['pedido_id']; ?>)">
                                                    <i class="fas fa-times me-1"></i>Cancelar
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <?php if ($total_paginas > 1): ?>
                    <nav aria-label="Navegación de páginas" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?pagina=<?php echo $i; ?><?php echo !empty($filtros['estado']) ? '&estado=' . $filtros['estado'] : ''; ?><?php echo !empty($filtros['fecha_inicio']) ? '&fecha_inicio=' . $filtros['fecha_inicio'] : ''; ?><?php echo !empty($filtros['fecha_fin']) ? '&fecha_fin=' . $filtros['fecha_fin'] : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
           
        </div>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger mt-3">
            <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success mt-3">
            <?php 
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>
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

// Mostrar mensajes de éxito/error si existen
<?php if (isset($_SESSION['error'])): ?>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '<?php echo $_SESSION['error']; ?>',
        confirmButtonColor: '#8B4513'
    });
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '<?php echo $_SESSION['success']; ?>',
        confirmButtonColor: '#8B4513'
    });
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
</script>

<?php require_once 'views/templates/footer.php'; ?> 