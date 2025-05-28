<?php require_once 'views/templates/header.php'; ?>

<div class="container py-5">
    <h1 class="mb-4">Mis Pedidos</h1>

    <?php if (SessionHelper::hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo SessionHelper::getFlash('success'); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($pedidos)): ?>
        <div class="alert alert-info">
            No tienes pedidos realizados aún.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Número de Pedido</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td>#<?php echo $pedido['pedido_id']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($pedido['fecha_creacion'])); ?></td>
                            <td>€<?php echo number_format($pedido['total'], 2); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $pedido['estado'] === 'completado' ? 'success' : 'warning'; ?>">
                                    <?php echo ucfirst($pedido['estado']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>perfil/pedido-detalle/<?php echo $pedido['pedido_id']; ?>" 
                                   class="btn btn-sm btn-info">
                                    Ver Detalles
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($paginas > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $paginas; $i++): ?>
                        <li class="page-item <?php echo $pagina_actual == $i ? 'active' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once 'views/templates/footer.php'; ?> 