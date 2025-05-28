<?php require_once 'views/templates/header.php'; ?>

<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Inicio</a></li>
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>productos">Productos</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($producto['nombre']); ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Imagen del producto -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <?php
                $img = $producto['imagen_url'];
                if (empty($img)) {
                    $img = BASE_URL . 'assets/img/no-image.jpg';
                } elseif (strpos($img, 'http') !== 0) {
                    $img = BASE_URL . ltrim($img, '/');
                }
                ?>
                <img src="<?php echo htmlspecialchars($img); ?>"
                     class="card-img-top img-fluid"
                     alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
            </div>
        </div>

        <!-- Detalles del producto -->
        <div class="col-md-6">
            <h1 class="mb-3"><?php echo htmlspecialchars($producto['nombre']); ?></h1>
            
            <div class="mb-3">
                <span class="badge bg-secondary"><?php echo htmlspecialchars($producto['categoria_nombre']); ?></span>
                <span class="badge bg-info"><?php echo $producto['grado_alcoholico']; ?>°</span>
            </div>

            <p class="text-muted mb-3">
                <?php if (isset($producto['marca']) && $producto['marca']): ?>
                    Marca: <?php echo htmlspecialchars($producto['marca']); ?><br>
                <?php endif; ?>
                <?php if (isset($producto['pais_origen']) && $producto['pais_origen']): ?>
                    País de origen: <?php echo htmlspecialchars($producto['pais_origen']); ?><br>
                <?php endif; ?>
                <?php if (isset($producto['volumen']) && $producto['volumen']): ?>
                    Volumen: <?php echo $producto['volumen']; ?>ml
                <?php endif; ?>
            </p>

            <div class="mb-4">
                <?php if (isset($producto['precio_promocion']) && $producto['precio_promocion'] < $producto['precio']): ?>
                    <span class="text-danger fw-bold fs-4">€<?php echo number_format($producto['precio_promocion'], 2); ?></span>
                    <span class="text-muted text-decoration-line-through ms-2">€<?php echo number_format($producto['precio'], 2); ?></span>
                <?php else: ?>
                    <span class="fw-bold fs-4">€<?php echo number_format($producto['precio'], 2); ?></span>
                <?php endif; ?>
            </div>

            <!-- Formulario para agregar al carrito -->
            <form id="formCarrito" class="mb-4">
                <input type="hidden" name="producto_id" value="<?php echo $producto['producto_id']; ?>">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="cantidad" class="col-form-label">Cantidad:</label>
                    </div>
                    <div class="col-auto">
                        <input type="number" 
                               id="cantidad" 
                               name="cantidad" 
                               class="form-control" 
                               value="1" 
                               min="1" 
                               max="<?php echo $producto['stock']; ?>">
                    </div>
                    <div class="col-auto">
                        <span class="form-text">
                            <?php echo $producto['stock']; ?> unidades disponibles
                        </span>
                    </div>
                </div>

                <div id="cantidadAlerta" class="cantidad-alerta d-none">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="cantidadAlertaMsg"></span>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" 
                            class="btn btn-primary btn-lg flex-grow-1" 
                            <?php echo $producto['stock'] <= 0 ? 'disabled' : ''; ?>>
                        <?php echo $producto['stock'] > 0 ? 'Añadir al carrito' : 'Agotado'; ?>
                    </button>
                    
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <button type="button" 
                                class="btn btn-outline-danger btn-lg toggle-favorite" 
                                data-product-id="<?php echo $producto['producto_id']; ?>"
                                title="<?php echo isset($producto['es_favorito']) && $producto['es_favorito'] ? 'Eliminar de favoritos' : 'Añadir a favoritos'; ?>">
                            <i class="<?php echo isset($producto['es_favorito']) && $producto['es_favorito'] ? 'fas' : 'far'; ?> fa-heart"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Descripción -->
            <div class="mb-4">
                <h4>Descripción</h4>
                <p><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
            </div>

            <!-- Características adicionales -->
            <?php if (!empty($producto['caracteristicas'])): ?>
            <div class="mb-4">
                <h4>Características</h4>
                <p><?php echo nl2br(htmlspecialchars($producto['caracteristicas'])); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Productos relacionados -->
    <?php if (!empty($relacionados)): ?>
    <div class="mt-5">
        <h3 class="mb-4">Productos relacionados</h3>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php foreach ($relacionados as $relacionado): ?>
                <div class="col">
                    <div class="card h-100">
                        <?php if ($relacionado['imagen_url']): ?>
                            <img src="<?php echo BASE_URL . 'uploads/' . $relacionado['imagen_url']; ?>" 
                                 class="card-img-top" 
                                 alt="<?php echo htmlspecialchars($relacionado['nombre']); ?>">
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="<?php echo BASE_URL; ?>productos/detalle/<?php echo $relacionado['producto_id']; ?>" 
                                   class="text-decoration-none">
                                    <?php echo htmlspecialchars($relacionado['nombre']); ?>
                                </a>
                            </h5>
                            
                            <p class="card-text">
                                <?php if (isset($relacionado['precio_promocion']) && $relacionado['precio_promocion'] < $relacionado['precio']): ?>
                                    <span class="text-decoration-line-through text-muted">
                                        €<?php echo number_format($relacionado['precio'], 2); ?>
                                    </span>
                                    <span class="ms-2 text-danger fw-bold">
                                        €<?php echo number_format($relacionado['precio_promocion'], 2); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="fw-bold">
                                        €<?php echo number_format($relacionado['precio'], 2); ?>
                                    </span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
document.getElementById('formCarrito').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('<?php echo BASE_URL; ?>carrito/agregar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams(formData)
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
                cartBadge.textContent = currentCount + parseInt(formData.get('cantidad'));
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
            <strong>Error:</strong> Debes iniciar sesión para agregar productos al carrito
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(alertDiv);

        // Eliminar la alerta después de 3 segundos
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const favButton = document.querySelector('.toggle-favorite');
    if (favButton) {
        favButton.addEventListener('click', async function(e) {
            e.preventDefault();
            try {
                const response = await fetch('<?php echo BASE_URL; ?>favoritos/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        producto_id: this.dataset.productId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Cambiar el icono
                    const icon = this.querySelector('i');
                    icon.classList.toggle('far');
                    icon.classList.toggle('fas');
                    
                    // Actualizar el título del botón
                    this.title = icon.classList.contains('fas') ? 'Eliminar de favoritos' : 'Añadir a favoritos';
                    
                    // Mostrar mensaje
                    Swal.fire({
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500,
                        position: 'top-end',
                        toast: true
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Ha ocurrido un error',
                        position: 'top-end',
                        toast: true,
                        timer: 3000
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ha ocurrido un error al procesar tu solicitud',
                    position: 'top-end',
                    toast: true,
                    timer: 3000
                });
            }
        });
    }
});

const inputCantidad = document.getElementById('cantidad');
const alerta = document.getElementById('cantidadAlerta');
const alertaMsg = document.getElementById('cantidadAlertaMsg');
const stock = <?php echo (int)$producto['stock']; ?>;

inputCantidad.addEventListener('input', function() {
    let val = parseInt(this.value);
    if (val > stock) {
        alertaMsg.textContent = `El valor debe ser menor de o igual a ${stock}`;
        alerta.classList.remove('d-none');
        this.classList.add('is-invalid');
    } else if (val < 1) {
        alertaMsg.textContent = 'El valor debe ser mayor de o igual a 1';
        alerta.classList.remove('d-none');
        this.classList.add('is-invalid');
    } else {
        alerta.classList.add('d-none');
        this.classList.remove('is-invalid');
    }
});
</script>

<?php require_once 'views/templates/footer.php'; ?> 