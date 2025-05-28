<?php require_once 'views/templates/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <!-- Lista de productos como artículos -->
        <div class="col-lg-8">
            <h1 class="h2 mb-4">Blog del Vino</h1>

            <div class="card mb-4 border-warning shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-4 d-none d-md-block">
                        <img src="https://cdn-icons-png.flaticon.com/512/2917/2917995.png" alt="Advertencia alcohol" width="90" height="90" style="filter: drop-shadow(0 2px 4px #0002);">
                    </div>
                    <div>
                        <h2 class="h5 text-warning mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Información importante sobre el consumo de alcohol</h2>
                        <ul class="mb-2" style="font-size:1.08rem;">
                            <li><i class="fas fa-user-shield text-danger me-2"></i><strong> Solo para mayores de edad:</strong> Prohibida la venta y consumo de alcohol a menores de 18 años.</li>
                            <li><i class="fas fa-glass-cheers text-primary me-2"></i><strong> Consumo moderado:</strong> Disfruta con responsabilidad. El abuso de alcohol puede causar daños físicos y mentales.</li>
                            <li><i class="fas fa-car-crash text-danger me-2"></i><strong> No conduzcas bajo los efectos del alcohol:</strong> Aumenta el riesgo de accidentes.</li>
                            <li><i class="fas fa-baby text-warning me-2"></i><strong> Embarazo y salud:</strong> Mujeres embarazadas y personas con enfermedades crónicas deben evitar el alcohol.</li>
                            <li><i class="fas fa-user-md text-success me-2"></i><strong> Consulta profesional:</strong> Si tienes dudas, consulta a un médico o especialista.</li>
                            <li><i class="fas fa-heartbeat text-danger me-2"></i><strong> Riesgo de adicción:</strong> El consumo excesivo puede generar dependencia y problemas familiares o sociales.</li>
                            <li><i class="fas fa-ban text-secondary me-2"></i><strong> Prohibido para menores:</strong> Respeta la ley y protege a los jóvenes.</li>
                        </ul>
                        <p class="mb-0 text-muted" style="font-size:0.98rem;"><i class="fas fa-info-circle me-1"></i> Beber con moderación es parte de un estilo de vida saludable. ¡Cuida tu salud y la de los demás!</p>
                    </div>
                </div>
            </div>

            

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
                        <?php foreach ($categorias as $categoria): ?>
                            <a href="<?php echo BASE_URL; ?>blog/categoria/<?php echo $categoria['categoria_id']; ?>" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <?php echo $categoria['nombre']; ?>
                                <?php if (isset($categoria['total_productos'])): ?>
                                    <span class="badge bg-primary rounded-pill">
                                        <?php echo $categoria['total_productos']; ?>
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
    height: auto;
    width: auto;
    max-height: 300px;
    max-width: 100%;
    object-fit: contain;
    background: #f5f5f5;
}
.blog-img-wrapper {
    height: 300px;
    background: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-top-left-radius: 0.375rem;
    border-top-right-radius: 0.375rem;
}
@media (max-width: 768px) {
    .card-img-top.blog-img, .blog-img-wrapper {
        height: 180px;
        max-height: 180px;
    }
}
</style>

<?php require_once 'views/templates/footer.php'; ?> 