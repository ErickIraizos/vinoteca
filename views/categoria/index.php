<?php require_once 'views/templates/header.php'; ?>

<div class="container py-5">
    <h1 class="h2 mb-4">Categorías</h1>

    <div class="row mb-5">
        <div class="col-12 text-center">
            <span class="badge bg-warning text-dark fs-5 mb-2"><i class="fas fa-star me-2"></i>¡Descubre nuestras categorías más populares!</span>
            <p class="lead">Explora una amplia variedad de vinos, licores y destilados seleccionados especialmente para ti. Encuentra la categoría que más se adapte a tu gusto y déjate sorprender por nuestras recomendaciones.</p>
        </div>
    </div>

    <div class="row g-4">
        <?php foreach ($categorias as $categoria): ?>
            <div class="col-md-4">
                <a href="<?php echo BASE_URL; ?>categoria/<?php echo $categoria['categoria_id']; ?>" 
                   class="text-decoration-none">
                    <div class="card h-100 category-card">
                        <?php if (!empty($categoria['imagen_url'])): ?>
                            <img src="<?php echo htmlspecialchars($categoria['imagen_url']); ?>" 
                                 class="card-img-top" 
                                 alt="<?php echo $categoria['nombre']; ?>">
                        <?php else: ?>
                            <img src="<?php echo BASE_URL; ?>assets/img/no-image.jpg" 
                                 class="card-img-top" 
                                 alt="Imagen no disponible">
                        <?php endif; ?>
                        <div class="card-body text-center">
                            <h2 class="h5 card-title"><?php echo $categoria['nombre']; ?></h2>
                            <p class="card-text text-muted">
                                <?php echo $categoria['total_productos']; ?> productos
                            </p>
                            <?php if ($categoria['descripcion']): ?>
                                <p class="card-text small">
                                    <?php echo substr($categoria['descripcion'], 0, 100) . '...'; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer text-center bg-transparent">
                            <a href="<?php echo BASE_URL; ?>categoria/ver/<?php echo $categoria['categoria_id']; ?>" class="btn btn-outline-primary btn-sm">Ver productos</a>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Información adicional -->
    <div class="row mt-5">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-wine-bottle fa-3x text-primary mb-3"></i>
                    <h3 class="h5">Gran selección</h3>
                    <p class="text-muted mb-0">Más de 1000 referencias de vinos y licores de todo el mundo.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-truck fa-3x text-primary mb-3"></i>
                    <h3 class="h5">Envío rápido</h3>
                    <p class="text-muted mb-0">Entrega en 24/48h en península. Envío gratis en pedidos superiores a <strong>60€</strong>.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-medal fa-3x text-primary mb-3"></i>
                    <h3 class="h5">Calidad garantizada</h3>
                    <p class="text-muted mb-0">Todos nuestros productos son seleccionados cuidadosamente y almacenados en condiciones óptimas.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.category-card {
    transition: transform 0.2s;
    border: none;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.category-card:hover {
    transform: translateY(-5px);
}

.category-card .card-img-top {
    height: 200px;
    object-fit: cover;
}
</style>

<?php require_once 'views/templates/footer.php'; ?> 