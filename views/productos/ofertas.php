<?php require_once 'views/templates/header.php'; ?>

<div class="container py-5">
    <!-- Encabezado de la página -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <span class="badge bg-danger text-white fs-5 mb-2"><i class="fas fa-tags me-2"></i>¡Aprovecha nuestras ofertas exclusivas!</span>
            <p class="lead">Descubre descuentos especiales en vinos y licores seleccionados. ¡No dejes pasar la oportunidad de llevarte tus favoritos a un precio único!</p>
        </div>
    </div>

    <div class="row">
        <!-- Filtros laterales -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Filtrar por</h5>
                    
                    <!-- Filtro por categorías -->
                    <div class="mb-4">
                        <h6 class="mb-3">Categorías</h6>
                        <div class="list-group">
                            <?php foreach ($categorias as $categoria): ?>
                                <a href="<?php echo BASE_URL; ?>producto/ofertas?categoria=<?php echo $categoria['categoria_id']; ?>" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center<?php echo (isset($categoria_actual) && $categoria_actual == $categoria['categoria_id']) ? ' active' : ''; ?>">
                                    <?php echo $categoria['nombre']; ?>
                                    <span class="badge bg-primary rounded-pill"><?php echo $categoria['total_productos']; ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de productos en oferta -->
        <div class="col-lg-9">
            <?php if (empty($ofertas)): ?>
                <div class="alert alert-info">
                    No hay ofertas disponibles en este momento.
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($ofertas as $producto): ?>
                        <div class="col">
                            <div class="card h-100 product-card">
                                <!-- Etiqueta de descuento -->
                                <div class="discount-badge">
                                    -<?php echo round($producto['descuento_porcentaje']); ?>%
                                </div>
                                
                                <!-- Imagen del producto -->
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
                                
                                <!-- Detalles del producto -->
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $producto['nombre']; ?></h5>
                                    <p class="card-text text-muted mb-2"><?php echo $producto['categoria_nombre']; ?></p>
                                    
                                    <!-- Precios -->
                                    <div class="mb-3">
                                        <?php if (isset($producto['precio_promocion']) && $producto['precio_promocion'] < $producto['precio']): ?>
                                            <span class="text-danger fw-bold">€<?php echo number_format($producto['precio_promocion'], 2); ?></span>
                                            <span class="text-muted text-decoration-line-through ms-1">€<?php echo number_format($producto['precio'], 2); ?></span>
                                        <?php else: ?>
                                            €<?php echo number_format($producto['precio'], 2); ?>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Botones de acción -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="<?php echo BASE_URL; ?>producto/detalle/<?php echo $producto['producto_id']; ?>" 
                                           class="btn btn-outline-primary">Ver Detalles</a>
                                        <button class="btn btn-primary add-to-cart" 
                                                data-producto-id="<?php echo $producto['producto_id']; ?>">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Paginación -->
                <?php if (isset($paginas) && $paginas > 1): ?>
                    <nav class="mt-5">
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
    </div>
</div>

<script>
function agregarAlCarrito(productoId) {
    fetch('<?php echo BASE_URL; ?>carrito/agregar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `producto_id=${productoId}&cantidad=1`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Crear y mostrar mensaje de éxito
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
            alertDiv.style.zIndex = '1050';
            alertDiv.innerHTML = `
                <strong>¡Éxito!</strong> ${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            document.body.appendChild(alertDiv);

            // Actualizar el contador del carrito en el header si existe
            const cartBadge = document.querySelector('.cart-count');
            if (cartBadge) {
                const currentCount = parseInt(cartBadge.textContent || '0');
                cartBadge.textContent = currentCount + 1;
            }

            // Eliminar la alerta después de 3 segundos
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        } else {
            // Mostrar mensaje de error
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
            alertDiv.style.zIndex = '1050';
            alertDiv.innerHTML = `
                <strong>Error:</strong> ${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            document.body.appendChild(alertDiv);

            // Eliminar la alerta después de 3 segundos
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Mostrar mensaje de error
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
        alertDiv.style.zIndex = '1050';
        alertDiv.innerHTML = `
            <strong>Error:</strong> Ocurrió un error al agregar el producto al carrito
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(alertDiv);

        // Eliminar la alerta después de 3 segundos
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    });
}

// Asignar evento a los botones de añadir al carrito
const addToCartButtons = document.querySelectorAll('.add-to-cart');
addToCartButtons.forEach(btn => {
    btn.addEventListener('click', function() {
        const productoId = this.getAttribute('data-producto-id');
        agregarAlCarrito(productoId);
    });
});
</script>

<?php require_once 'views/templates/footer.php'; ?> 