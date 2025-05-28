<?php require_once 'views/templates/header.php'; ?>

<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $categoria['nombre']; ?></li>
        </ol>
    </nav>

    <h1 class="mb-4"><?php echo $categoria['nombre']; ?></h1>
    
    <?php if (!empty($categoria['descripcion'])): ?>
        <p class="lead mb-5"><?php echo $categoria['descripcion']; ?></p>
    <?php endif; ?>

    <?php if (empty($productos)): ?>
        <div class="alert alert-info">
            No hay productos disponibles en esta categoría.
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            <?php foreach ($productos as $producto): ?>
                <div class="col">
                    <div class="card h-100 product-card">
                        <?php if (isset($producto['descuento_porcentaje']) && $producto['descuento_porcentaje'] > 0): ?>
                            <div class="discount-badge">
                                -<?php echo round($producto['descuento_porcentaje']); ?>%
                            </div>
                        <?php endif; ?>
                        
                        <img src="<?php echo htmlspecialchars($producto['imagen_url']); ?>" 
                             class="card-img-top" 
                             alt="<?php echo $producto['nombre']; ?>">
                             
                        <div class="card-body">
                            <h2 class="card-title h5"><?php echo $producto['nombre']; ?></h2>
                            <p class="card-text small text-muted"><?php echo substr($producto['descripcion'], 0, 100) . '...'; ?></p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="price-group">
                                    <?php if (isset($producto['precio_promocion']) && $producto['precio_promocion'] < $producto['precio']): ?>
                                        <span class="text-danger fw-bold">€<?php echo number_format($producto['precio_promocion'], 2); ?></span>
                                        <span class="text-muted text-decoration-line-through ms-1">€<?php echo number_format($producto['precio'], 2); ?></span>
                                    <?php else: ?>
                                        €<?php echo number_format($producto['precio'], 2); ?>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo BASE_URL . 'producto/detalle/' . $producto['producto_id']; ?>" 
                                   class="btn btn-outline-primary btn-sm">Ver Detalles</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($paginas > 1): ?>
            <nav aria-label="Navegación de páginas" class="mt-5">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $paginas; $i++): ?>
                        <li class="page-item <?php echo $i == $pagina_actual ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo BASE_URL . 'categoria/' . $categoria['categoria_id'] . '?pagina=' . $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once VIEWS_PATH . '/partials/footer.php'; ?> 