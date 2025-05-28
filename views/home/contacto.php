<?php require_once 'views/templates/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mb-4">
            <h1 class="h2 mb-4">Contacto</h1>
            
            <?php if (SessionHelper::getFlash('success')): ?>
                <div class="alert alert-success">
                    <?php echo SessionHelper::getFlash('success'); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>home/contacto" method="POST" id="contactForm">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre completo</label>
                            <input type="text" class="form-control <?php echo isset($errores['nombre']) ? 'is-invalid' : ''; ?>" 
                                   id="nombre" name="nombre" value="<?php echo $old['nombre'] ?? ''; ?>" required>
                            <?php if (isset($errores['nombre'])): ?>
                                <div class="invalid-feedback"><?php echo $errores['nombre']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control <?php echo isset($errores['email']) ? 'is-invalid' : ''; ?>" 
                                   id="email" name="email" value="<?php echo $old['email'] ?? ''; ?>" required>
                            <?php if (isset($errores['email'])): ?>
                                <div class="invalid-feedback"><?php echo $errores['email']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="asunto" class="form-label">Asunto</label>
                            <input type="text" class="form-control <?php echo isset($errores['asunto']) ? 'is-invalid' : ''; ?>" 
                                   id="asunto" name="asunto" value="<?php echo $old['asunto'] ?? ''; ?>" required>
                            <?php if (isset($errores['asunto'])): ?>
                                <div class="invalid-feedback"><?php echo $errores['asunto']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="mensaje" class="form-label">Mensaje</label>
                            <textarea class="form-control <?php echo isset($errores['mensaje']) ? 'is-invalid' : ''; ?>" 
                                      id="mensaje" name="mensaje" rows="5" required><?php echo $old['mensaje'] ?? ''; ?></textarea>
                            <?php if (isset($errores['mensaje'])): ?>
                                <div class="invalid-feedback"><?php echo $errores['mensaje']; ?></div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-primary">Enviar mensaje</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Información de contacto</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                            <?php echo EMPRESA_DIRECCION; ?>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-phone me-2 text-primary"></i>
                            <?php echo EMPRESA_TELEFONO; ?>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-envelope me-2 text-primary"></i>
                            <?php echo EMPRESA_EMAIL; ?>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-clock me-2 text-primary"></i>
                            Lunes a Viernes: 9:00 - 20:00<br>
                            Sábados: 10:00 - 14:00
                        </li>
                    </ul>

                    <h5 class="card-title mb-4">Síguenos en redes sociales</h5>
                    <div class="social-links">
                        <a href="<?php echo EMPRESA_FACEBOOK; ?>" class="btn btn-outline-primary me-2" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="<?php echo EMPRESA_TWITTER; ?>" class="btn btn-outline-info me-2" target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="<?php echo EMPRESA_INSTAGRAM; ?>" class="btn btn-outline-danger me-2" target="_blank">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="<?php echo EMPRESA_WHATSAPP; ?>" class="btn btn-outline-success" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mapa -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Nuestra ubicación</h5>
                    <div id="map" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para el mapa -->
<script src="https://maps.googleapis.com/maps/api/js?key=TU_API_KEY"></script>
<script>
function initMap() {
    const location = { lat: EMPRESA_LAT, lng: EMPRESA_LNG };
    const map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: location,
    });
    const marker = new google.maps.Marker({
        position: location,
        map: map,
        title: EMPRESA_NOMBRE
    });
}

// Inicializar el mapa cuando se cargue la API
google.maps.event.addDomListener(window, 'load', initMap);
</script>

<?php require_once 'views/templates/footer.php'; ?> 