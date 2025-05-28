<style>
.review-product-img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    background: #fff;
}
.hero-section {
    margin-bottom: 2rem;
}
.hero-slider, .swiper, .swiper-container {
    width: 100%;
    min-height: 540px;
    height: 540px;
    max-height: 650px;
}
.hero-slide {
    min-height: 540px;
    height: 540px;
    max-height: 650px;
    background-size: cover;
    background-position: center center;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: flex-start;
}
.hero-slide::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.25); /* Overlay oscuro */
    z-index: 1;
    border-radius: 0.5rem;
}
.hero-content {
    position: relative;
    z-index: 2;
    max-width: 500px;
    margin-left: 2rem;
    margin-top: 2rem;
    background: rgba(255,255,255,0.85);
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.10);
    padding: 2.5rem 2rem;
}
.hero-content h1 {
    font-size: 2.8rem;
    font-weight: 700;
    color: #2c2c2c;
}
.hero-content p {
    font-size: 1.25rem;
    color: #444;
}
.hero-content .btn {
    font-size: 1.1rem;
    padding: 0.7rem 2.2rem;
    border-radius: 8px;
    background: #8B4513;
    border: none;
    color: #fff;
    font-weight: 600;
    margin-top: 1.2rem;
}
@media (max-width: 900px) {
    .hero-content {
        margin-left: 0.5rem;
        margin-top: 0.5rem;
        padding: 1.2rem 0.7rem;
    }
    .hero-content h1 {
        font-size: 2rem;
    }
    .hero-slider, .hero-slide {
        min-height: 320px;
        height: 320px;
    }
}
</style>

<!-- Hero Section con Carrusel -->
<section class="hero-section">
    <div class="swiper hero-slider">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="hero-slide" style="background-image: url('https://www.vinoskichak.com/cdn/shop/articles/wines-1761613_1280.jpg?v=1713315937&width=1100');">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="hero-content bg-white bg-opacity-75 p-4 rounded">
                                    <h1 class="display-4 mb-3">Vinos de calidad</h1>
                                    <p class="lead mb-4">Descubre nuestra selección de vinos exclusivos.</p>
                                    <a href="http://localhost/vino/producto" class="btn btn-primary btn-lg">Ver más</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="hero-slide" style="background-image: url('https://escuelaversailles.com/wp-content/uploads/tipos-de-vino.jpg');">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="hero-content bg-white bg-opacity-75 p-4 rounded">
                                    <h1 class="display-4 mb-3">Variedad de tipos</h1>
                                    <p class="lead mb-4">Tintos, blancos, rosados y mucho más para todos los gustos.</p>
                                    <a href="http://localhost/vino/producto" class="btn btn-primary btn-lg">Explorar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="hero-slide" style="background-image: url('https://www.saborusa.com/sv/wp-content/uploads/sites/4/2019/10/Vino-para-quedarse-Foto-destacada.png');">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="hero-content bg-white bg-opacity-75 p-4 rounded">
                                    <h1 class="display-4 mb-3">El vino ideal para cada ocasión</h1>
                                    <p class="lead mb-4">Encuentra el vino perfecto para celebrar o regalar.</p>
                                    <a href="http://localhost/vino/producto" class="btn btn-primary btn-lg">Comprar ahora</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</section>

<!-- Categorías Principales -->
<section class="categories-section py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">Nuestras Categorías</h2>
        <div class="row g-4">
            <?php foreach ($categorias as $categoria): ?>
                <div class="col-md-4">
                    <a href="<?php echo BASE_URL; ?>categoria/ver/<?php echo $categoria['categoria_id']; ?>" class="category-card text-decoration-none">
                        <div class="card h-100">
                            <?php if (!empty($categoria['imagen_url'])): ?>
                                <img src="<?php echo htmlspecialchars($categoria['imagen_url']); ?>" class="card-img-top" alt="<?php echo $categoria['nombre']; ?>">
                            <?php else: ?>
                                <img src="<?php echo BASE_URL; ?>assets/img/no-image.jpg" class="card-img-top" alt="Imagen no disponible">
                            <?php endif; ?>
                            <div class="card-body text-center">
                                <h3 class="card-title h5"><?php echo $categoria['nombre']; ?></h3>
                                <p class="card-text text-muted"><?php echo $categoria['total_productos']; ?> productos</p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Ofertas Especiales -->
<section class="offers-section py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-5">Ofertas Especiales</h2>
        <div class="swiper offers-slider">
            <div class="swiper-wrapper">
                <?php foreach ($ofertas as $oferta): ?>
                    <div class="swiper-slide">
                        <div class="card product-card h-100">
                            <?php if (isset($oferta['descuento_porcentaje']) && $oferta['descuento_porcentaje'] > 0): ?>
                                <div class="discount-badge">
                                    -<?php echo round($oferta['descuento_porcentaje']); ?>%
                                </div>
                            <?php endif; ?>
                            <img src="<?php echo htmlspecialchars($oferta['imagen_url']); ?>" class="card-img-top" alt="<?php echo $oferta['nombre']; ?>">
                            <div class="card-body">
                                <h3 class="card-title h5"><?php echo $oferta['nombre']; ?></h3>
                                <p class="card-text small text-muted"><?php echo $oferta['categoria_nombre']; ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="price-group">
                                        <?php if (isset($oferta['precio_promocion']) && $oferta['precio_promocion'] < $oferta['precio']): ?>
                                            <span class="text-danger fw-bold">€<?php echo number_format($oferta['precio_promocion'], 2); ?></span>
                                            <span class="text-muted text-decoration-line-through ms-1">€<?php echo number_format($oferta['precio'], 2); ?></span>
                                        <?php else: ?>
                                            €<?php echo number_format($oferta['precio'], 2); ?>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?php echo BASE_URL; ?>producto/detalle/<?php echo $oferta['producto_id']; ?>" class="btn btn-outline-primary btn-sm">Ver Detalles</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<!-- Reseñas Destacadas -->
<section class="reviews-section py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">Lo que dicen nuestros clientes</h2>
        <div class="swiper reviews-slider">
            <div class="swiper-wrapper">
                <?php foreach ($resenas as $resena): ?>
                    <div class="swiper-slide">
                        <div class="card review-card h-100">
                            <div class="card-body text-center">
                                <?php if (!empty($resena['producto_imagen'])): ?>
                                    <img src="<?php echo htmlspecialchars($resena['producto_imagen']); ?>" class="review-product-img mb-3" alt="<?php echo $resena['producto_nombre']; ?>">
                                <?php else: ?>
                                    <img src="<?php echo BASE_URL; ?>assets/img/no-image.jpg" class="review-product-img mb-3" alt="Imagen no disponible">
                                <?php endif; ?>
                                <div class="stars mb-3">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?php echo $i <= $resena['calificacion'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <p class="card-text mb-4"><?php echo substr($resena['comentario'], 0, 150) . '...'; ?></p>
                                <div class="review-author">
                                    <h5 class="mb-1"><?php echo $resena['usuario_nombre']; ?></h5>
                                    <p class="text-muted small mb-0">sobre <?php echo $resena['producto_nombre']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <h2 class="h3 mb-3">¡Suscríbete a nuestro newsletter!</h2>
                <p class="mb-0">Recibe las últimas novedades y ofertas especiales directamente en tu correo.</p>
            </div>
            <div class="col-md-6">
                <form id="newsletter-form-main" class="newsletter-form">
                    <div class="input-group">
                        <input type="email" class="form-control form-control-lg" placeholder="Tu correo electrónico" required>
                        <button class="btn btn-light btn-lg" type="submit">Suscribirse</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Inicialización de Swiper -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hero Slider
    new Swiper('.hero-slider', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.hero-slider .swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.hero-slider .swiper-button-next',
            prevEl: '.hero-slider .swiper-button-prev',
        },
    });

    // Offers Slider
    new Swiper('.offers-slider', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.offers-slider .swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            1024: {
                slidesPerView: 4,
            },
        },
    });

    // Reviews Slider
    new Swiper('.reviews-slider', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.reviews-slider .swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
        },
    });
});
</script> 