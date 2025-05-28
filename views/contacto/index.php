<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <h1 class="mb-4">Contacto</h1>
            
            <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="alert alert-<?php echo $_SESSION['mensaje']['tipo']; ?> alert-dismissible fade show">
                    <?php echo $_SESSION['mensaje']['texto']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['mensaje']); ?>
            <?php endif; ?>

            <form action="<?php echo BASE_URL; ?>contacto/enviar" method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="mb-3">
                    <label for="asunto" class="form-label">Asunto</label>
                    <input type="text" class="form-control" id="asunto" name="asunto" required>
                </div>
                
                <div class="mb-3">
                    <label for="mensaje" class="form-label">Mensaje</label>
                    <textarea class="form-control" id="mensaje" name="mensaje" rows="5" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Enviar mensaje</button>
            </form>
        </div>
        
        <div class="col-md-6">
            <div class="bg-light p-4 h-100">
                <h2 class="mb-4">Información de contacto</h2>
                
                <div class="mb-4">
                    <h5>Dirección</h5>
                    <p>Calle Principal 123<br>28001 Madrid, España</p>
                </div>
                
                <div class="mb-4">
                    <h5>Teléfono</h5>
                    <p><a href="tel:+34900123456">+34 900 123 456</a></p>
                </div>
                
                <div class="mb-4">
                    <h5>Email</h5>
                    <p><a href="mailto:info@vinoteca.com">info@vinoteca.com</a></p>
                </div>
                
                <div class="mb-4">
                    <h5>Horario de atención</h5>
                    <p>Lunes a Viernes: 9:00 - 20:00<br>
                    Sábados: 10:00 - 14:00</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Formulario de Reseñas Mejorado -->
<div class="row justify-content-center my-5">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-lg border-0">
            <div class="card-body p-4">
                <h2 class="mb-4 text-center">Deja tu reseña</h2>
                <?php if (!isset($_SESSION['usuario_id'])): ?>
                    <div class="alert alert-warning text-center">Debes iniciar sesión para dejar una reseña.</div>
                <?php else: ?>
                    <form action="<?php echo BASE_URL; ?>resena/crear" method="POST">
                        <div class="mb-3">
                            <label for="producto_id" class="form-label">Producto</label>
                            <select name="producto_id" id="producto_id" class="form-select" required>
                                <?php
                                $productoModel = new ProductoModel($this->db);
                                $productos = $productoModel->getAll(1, '', 100)['items'];
                                foreach ($productos as $producto): ?>
                                    <option value="<?php echo $producto['producto_id']; ?>"><?php echo htmlspecialchars($producto['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="calificacion" class="form-label">Calificación</label>
                            <select name="calificacion" id="calificacion" class="form-select" required>
                                <option value="1">1 ⭐</option>
                                <option value="2">2 ⭐⭐</option>
                                <option value="3">3 ⭐⭐⭐</option>
                                <option value="4">4 ⭐⭐⭐⭐</option>
                                <option value="5">5 ⭐⭐⭐⭐⭐</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="comentario" class="form-label">Comentario</label>
                            <textarea name="comentario" id="comentario" class="form-control" rows="4" required placeholder="Escribe aquí tu experiencia..."></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">Enviar reseña</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> 