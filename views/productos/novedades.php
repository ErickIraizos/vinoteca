<?php require_once 'views/templates/header.php'; ?>

<?php
// Verificar que las variables necesarias estén definidas
if (!isset($novedades) || !isset($categorias)) {
    throw new Exception('Datos necesarios no disponibles');
}
?>

<div class="container py-5">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <span class="badge bg-success text-white fs-5 mb-2"><i class="fas fa-star me-2"></i>¡Novedades recién llegadas!</span>
            <p class="lead">Explora los productos más nuevos de nuestra tienda. Vinos, licores y destilados que acaban de incorporarse a nuestro catálogo. ¡Sé el primero en probarlos!</p>
        </div>
    </div>
    <div class="row">
        <!-- Sidebar con filtros -->
        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Categorías</h5>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($categorias as $categoria): ?>
                        <a href="<?php echo BASE_URL; ?>producto/novedades?categoria=<?php echo $categoria['categoria_id']; ?>" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <?php echo $categoria['nombre']; ?>
                            <span class="badge bg-primary rounded-pill"><?php echo $categoria['total_productos']; ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Lista de productos -->
        <div class="col-lg-9">
            <h1 class="mb-4"><?php echo $title; ?></h1>
            <p class="lead mb-4"><?php echo $description; ?></p>

            <?php if (empty($novedades)): ?>
                <div class="alert alert-info">
                    No hay productos nuevos disponibles en este momento.
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($novedades as $producto): ?>
                        <div class="col">
                            <div class="card h-100 product-card">
                                <?php if (isset($producto['descuento_porcentaje']) && $producto['descuento_porcentaje'] > 0): ?>
                                    <div class="discount-badge">
                                        -<?php echo round($producto['descuento_porcentaje']); ?>%
                                    </div>
                                <?php endif; ?>
                                
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
                                     alt="<?php echo $producto['nombre']; ?>">
                                
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $producto['nombre']; ?></h5>
                                    <p class="card-text text-muted small"><?php echo $producto['categoria_nombre']; ?></p>
                                    <p class="card-text"><?php echo substr($producto['descripcion'], 0, 100) . '...'; ?></p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div class="price-group">
                                            <?php if (isset($producto['precio_promocion']) && $producto['precio_promocion'] < $producto['precio']): ?>
                                                <span class="text-danger fw-bold">€<?php echo number_format($producto['precio_promocion'], 2); ?></span>
                                                <span class="text-muted text-decoration-line-through ms-1">€<?php echo number_format($producto['precio'], 2); ?></span>
                                            <?php else: ?>
                                                €<?php echo number_format($producto['precio'], 2); ?>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <a href="<?php echo BASE_URL; ?>producto/detalle/<?php echo $producto['producto_id']; ?>" 
                                           class="btn btn-outline-primary">Ver Detalles</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (isset($pagina_actual) && isset($total_paginas) && $total_paginas > 1): ?>
                    <nav aria-label="Navegación de páginas" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
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
    </div>
</div>

<?php require_once 'views/templates/footer.php'; ?> 