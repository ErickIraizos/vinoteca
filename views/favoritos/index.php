<?php require_once 'views/templates/header.php'; ?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Mis Favoritos</h1>
        <a href="<?php echo BASE_URL; ?>productos" class="btn btn-outline-primary">
            <i class="fas fa-wine-bottle me-2"></i>Ver más productos
        </a>
    </div>

    <?php if (SessionHelper::hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo SessionHelper::getFlash('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($favoritos)): ?>
        <div class="alert alert-info">
            <i class="fas fa-heart me-2"></i>
            No tienes productos favoritos aún.
            <a href="<?php echo BASE_URL; ?>productos" class="alert-link">Explora nuestro catálogo</a>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($favoritos as $producto): ?>
                <div class="col">
                    <div class="card h-100 producto-card">
                        <!-- Imagen del producto -->
                        <div class="position-relative">
                            <?php
                            $img = $producto['imagen_url'];
                            if (empty($img)) {
                                $img = BASE_URL . 'assets/img/no-image.jpg';
                            } elseif (strpos($img, 'http') !== 0) {
                                $img = BASE_URL . ltrim($img, '/');
                            }
                            ?>
                            <img src="<?php echo htmlspecialchars($img); ?>"
                                 class="card-img-top"
                                 alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                            
                            <?php if (isset($producto['descuento_porcentaje']) && $producto['descuento_porcentaje'] > 0): ?>
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-danger">-<?php echo round($producto['descuento_porcentaje']); ?>%</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="<?php echo BASE_URL; ?>productos/detalle/<?php echo $producto['producto_id']; ?>" 
                                   class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($producto['nombre']); ?>
                                </a>
                            </h5>
                            
                            <p class="card-text text-muted mb-2">
                                <?php echo htmlspecialchars($producto['categoria_nombre']); ?>
                            </p>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <?php if (isset($producto['precio_promocion']) && $producto['precio_promocion'] < $producto['precio']): ?>
                                        <span class="text-decoration-line-through text-muted">
                                            €<?php echo number_format($producto['precio'], 2); ?>
                                        </span>
                                        <span class="ms-2 text-danger fw-bold">
                                            €<?php echo number_format($producto['precio_promocion'], 2); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="fw-bold">
                                            €<?php echo number_format($producto['precio'], 2); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <span class="badge bg-<?php echo $producto['stock'] > 0 ? 'success' : 'danger'; ?>">
                                    <?php echo $producto['stock'] > 0 ? 'Disponible' : 'Agotado'; ?>
                                </span>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="<?php echo BASE_URL; ?>productos/detalle/<?php echo $producto['producto_id']; ?>" 
                                   class="btn btn-outline-primary flex-grow-1">
                                    <i class="fas fa-eye me-1"></i>Ver detalles
                                </a>
                                <a href="<?php echo BASE_URL; ?>favoritos/eliminar/<?php echo $producto['producto_id']; ?>" 
                                   class="btn btn-outline-danger"
                                   onclick="return confirm('¿Estás seguro de que deseas eliminar este producto de favoritos?')">
                                    <i class="fas fa-heart-broken"></i>
                                </a>
                            </div>
                        </div>

                        <div class="card-footer text-muted small">
                            <i class="far fa-clock me-1"></i>
                            Añadido el <?php echo date('d/m/Y', strtotime($producto['fecha_agregado'])); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($paginas > 1): ?>
            <nav aria-label="Navegación de páginas" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $paginas; $i++): ?>
                        <li class="page-item <?php echo $i == $pagina_actual ? 'active' : ''; ?>">
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

<style>
.producto-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.producto-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.producto-card .card-img-top {
    height: 200px;
    object-fit: cover;
}
</style>

<?php require_once 'views/templates/footer.php'; ?> 