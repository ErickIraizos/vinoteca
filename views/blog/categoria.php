<?php require_once 'views/templates/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <!-- Lista de productos como artículos -->
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>blog">Blog</a></li>
                    <li class="breadcrumb-item active"><?php echo $categoria['nombre']; ?></li>
                </ol>
            </nav>

            <h1 class="h2 mb-4"><?php echo $categoria['nombre']; ?></h1>
            
            <?php if ($categoria['descripcion']): ?>
                <p class="lead mb-4"><?php echo $categoria['descripcion']; ?></p>
            <?php endif; ?>

            <?php if (empty($articulos)): ?>
                <div class="alert alert-info">
                    No hay productos en esta categoría.
                </div>
            <?php else: ?>
                <?php foreach ($articulos as $articulo): ?>
                    <article class="card mb-4">
                        <?php
                        $img = $articulo['imagen_url'];
                        if (empty($img)) {
                            $img = BASE_URL . 'assets/img/no-image.jpg';
                        } elseif (strpos($img, 'http') !== 0) {
                            $img = BASE_URL . ltrim($img, '/');
                        }
                        ?>
                        <div class="blog-img-wrapper">
                            <?php if (isset($articulo['descuento_porcentaje']) && $articulo['descuento_porcentaje'] > 0): ?>
                                <div class="discount-badge">
                                    -<?php echo round($articulo['descuento_porcentaje']); ?>%
                                </div>
                            <?php endif; ?>
                            <img src="<?php echo htmlspecialchars($img); ?>"
                                 class="card-img-top blog-img"
                                 alt="<?php echo $articulo['nombre']; ?>">
                        </div>
                        
                        <div class="card-body">
                            <h2 class="card-title h4">
                                <a href="<?php echo BASE_URL; ?>producto/<?php echo $articulo['producto_id']; ?>" 
                                   class="text-decoration-none">
                                    <?php echo $articulo['nombre']; ?>
                                </a>
                            </h2>
                            
                            <div class="text-muted mb-3 small">
                                <i class="fas fa-wine-bottle me-2"></i>
                                <?php echo $articulo['marca']; ?>
                                
                                <?php if ($articulo['pais_origen']): ?>
                                    <i class="fas fa-globe-americas ms-3 me-2"></i>
                                    <?php echo $articulo['pais_origen']; ?>
                                <?php endif; ?>
                                
                                <?php if ($articulo['grado_alcoholico']): ?>
                                    <i class="fas fa-percent ms-3 me-2"></i>
                                    <?php echo $articulo['grado_alcoholico']; ?>°
                                <?php endif; ?>
                            </div>
                            
                            <p class="card-text">
                                <?php echo substr(strip_tags($articulo['descripcion']), 0, 200) . '...'; ?>
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="<?php echo BASE_URL; ?>producto/<?php echo $articulo['producto_id']; ?>" 
                                   class="btn btn-outline-primary">
                                    Leer más
                                </a>
                                <span class="h5 mb-0">
                                    <?php if (isset($articulo['precio_promocion']) && $articulo['precio_promocion'] < $articulo['precio']): ?>
                                        <span class="text-danger fw-bold">€<?php echo number_format($articulo['precio_promocion'], 2); ?></span>
                                        <span class="text-muted text-decoration-line-through ms-1">€<?php echo number_format($articulo['precio'], 2); ?></span>
                                    <?php else: ?>
                                        €<?php echo number_format($articulo['precio'], 2); ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>

                <!-- Paginación -->
                <?php if ($paginas > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $paginas; $i++): ?>
                                <li class="page-item <?php echo $i === $pagina_actual ? 'active' : ''; ?>">
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

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Buscador -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="card-title h5 mb-3">Buscar productos</h3>
                    <form action="<?php echo BASE_URL; ?>productos/buscar" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Buscar..." name="q">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Categorías -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="card-title h5 mb-3">Categorías</h3>
                    <div class="list-group list-group-flush">
                        <?php foreach ($categorias as $cat): ?>
                            <a href="<?php echo BASE_URL; ?>blog/categoria/<?php echo $cat['categoria_id']; ?>" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php echo $cat['categoria_id'] === $categoria['categoria_id'] ? 'active' : ''; ?>">
                                <?php echo $cat['nombre']; ?>
                                <?php if (isset($cat['total_productos'])): ?>
                                    <span class="badge bg-<?php echo $cat['categoria_id'] === $categoria['categoria_id'] ? 'light' : 'primary'; ?> rounded-pill">
                                        <?php echo $cat['total_productos']; ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title h5 mb-3">Suscríbete al Newsletter</h3>
                    <p class="card-text">Recibe las últimas novedades y ofertas en tu correo.</p>
                    <form id="newsletter-form" class="mt-3">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Tu email" required>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-img-top.blog-img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    background: #f5f5f5;
    display: block;
}
.blog-img-wrapper {
    position: relative;
    height: 300px;
    background: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-top-left-radius: 0.375rem;
    border-top-right-radius: 0.375rem;
}
.discount-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: #8B4513;
    color: #fff;
    padding: 6px 14px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 1rem;
    z-index: 2;
}
@media (max-width: 768px) {
    .card-img-top.blog-img, .blog-img-wrapper {
        height: 180px;
        max-height: 180px;
    }
}
</style>

<?php require_once 'views/templates/footer.php'; ?> 