<div class="container py-5">
    <div class="row">
        <!-- Filtros -->
        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Filtros</h5>
                    
                    <!-- Búsqueda -->
                    <form action="<?php echo BASE_URL; ?>productos" method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   name="buscar" 
                                   placeholder="Buscar vinos..."
                                   value="<?php echo htmlspecialchars($busqueda ?? ''); ?>">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Categorías -->
                    <h6 class="mb-3">Categorías</h6>
                    <div class="list-group mb-4">
                        <a href="<?php echo BASE_URL; ?>productos" 
                           class="list-group-item list-group-item-action <?php echo !isset($categoria_actual) ? 'active' : ''; ?>">
                            Todas las categorías
                        </a>
                        <?php foreach ($categorias as $categoria): ?>
                            <a href="<?php echo BASE_URL; ?>productos?categoria=<?php echo $categoria['categoria_id']; ?>" 
                               class="list-group-item list-group-item-action <?php echo (isset($categoria_actual) && $categoria_actual == $categoria['categoria_id']) ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($categoria['nombre']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <!-- Ordenar por -->
                    <h6 class="mb-3">Ordenar por</h6>
                    <form id="ordenForm">
                        <select class="form-select" name="orden" onchange="this.form.submit()">
                            <option value="nombre" <?php echo ($orden ?? '') === 'nombre' ? 'selected' : ''; ?>>Nombre A-Z</option>
                            <option value="nombre_desc" <?php echo ($orden ?? '') === 'nombre_desc' ? 'selected' : ''; ?>>Nombre Z-A</option>
                            <option value="precio" <?php echo ($orden ?? '') === 'precio' ? 'selected' : ''; ?>>Precio más bajo</option>
                            <option value="precio_desc" <?php echo ($orden ?? '') === 'precio_desc' ? 'selected' : ''; ?>>Precio más alto</option>
                            <option value="nuevo" <?php echo ($orden ?? '') === 'nuevo' ? 'selected' : ''; ?>>Más nuevos</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="col-lg-9">
            <?php if (empty($productos['items'])): ?>
                <div class="alert alert-info">
                    No se encontraron productos que coincidan con tu búsqueda.
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($productos['items'] as $producto): ?>
                        <div class="col">
                            <div class="card h-100">
                                <?php if ($producto['imagen_url']): ?>
                                    <img src="<?php echo htmlspecialchars($producto['imagen_url']); ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                                <?php else: ?>
                                    <img src="<?php echo BASE_URL; ?>assets/img/no-image.jpg" 
                                         class="card-img-top" 
                                         alt="Imagen no disponible">
                                <?php endif; ?>
                                
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="<?php echo BASE_URL; ?>productos/detalle/<?php echo $producto['producto_id']; ?>" 
                                           class="text-decoration-none">
                                            <?php echo htmlspecialchars($producto['nombre']); ?>
                                        </a>
                                    </h5>
                                    
                                    <p class="card-text text-muted mb-2">
                                        <?php echo htmlspecialchars($producto['marca']); ?> | 
                                        <?php echo $producto['grado_alcoholico']; ?>°
                                    </p>
                                    
                                    <p class="card-text">
                                        <?php if (isset($producto['precio_promocion']) && $producto['precio_promocion'] < $producto['precio']): ?>
                                            <span class="text-danger fw-bold">€<?php echo number_format($producto['precio_promocion'], 2); ?></span>
                                            <span class="text-muted text-decoration-line-through ms-1">€<?php echo number_format($producto['precio'], 2); ?></span>
                                        <?php else: ?>
                                            €<?php echo number_format($producto['precio'], 2); ?>
                                        <?php endif; ?>
                                    </p>
                                    <a href="<?php echo BASE_URL; ?>producto/detalle/<?php echo $producto['producto_id']; ?>" class="btn btn-outline-primary w-100 mb-2">Ver Detalles</a>
                                </div>
                                
                                <div class="card-footer bg-transparent border-top-0">
                                    <button class="btn btn-primary w-100" 
                                            onclick="agregarAlCarrito(<?php echo $producto['producto_id']; ?>)"
                                            <?php echo $producto['stock'] <= 0 ? 'disabled' : ''; ?>>
                                        <?php echo $producto['stock'] > 0 ? 'Añadir al carrito' : 'Agotado'; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Paginación -->
                <?php if ($productos['total_paginas'] > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $productos['total_paginas']; $i++): ?>
                                <li class="page-item <?php echo $pagina_actual == $i ? 'active' : ''; ?>">
                                    <a class="page-link" 
                                       href="?pagina=<?php echo $i; ?><?php echo isset($_GET['categoria']) ? '&categoria=' . $_GET['categoria'] : ''; ?>">
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

// Mantener los filtros al cambiar de página
document.querySelector('select[name="orden"]').addEventListener('change', function() {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('orden', this.value);
    window.location.href = '?' + urlParams.toString();
});
</script> 