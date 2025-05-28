<?php require_once 'views/templates/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h1 class="h3 mb-4 text-center">Mi Perfil</h1>

                    <?php if (SessionHelper::hasFlash('success')): ?>
                        <div class="alert alert-success">
                            <?php echo SessionHelper::getFlash('success'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (SessionHelper::hasFlash('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo SessionHelper::getFlash('error'); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo BASE_URL; ?>perfil/actualizar" method="POST" class="needs-validation" novalidate>
                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" 
                                   class="form-control <?php echo isset($errores['nombre']) ? 'is-invalid' : ''; ?>" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="<?php echo $usuario['nombre'] ?? ''; ?>" 
                                   required>
                            <?php if (isset($errores['nombre'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errores['nombre']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Apellido -->
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" 
                                   class="form-control <?php echo isset($errores['apellido']) ? 'is-invalid' : ''; ?>" 
                                   id="apellido" 
                                   name="apellido" 
                                   value="<?php echo $usuario['apellido'] ?? ''; ?>" 
                                   required>
                            <?php if (isset($errores['apellido'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errores['apellido']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Email (solo lectura) -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   value="<?php echo $usuario['email'] ?? ''; ?>" 
                                   readonly>
                            <div class="form-text">El correo electrónico no se puede modificar</div>
                        </div>

                        <!-- Dirección -->
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <textarea class="form-control <?php echo isset($errores['direccion']) ? 'is-invalid' : ''; ?>" 
                                      id="direccion" 
                                      name="direccion" 
                                      rows="2"><?php echo $usuario['direccion'] ?? ''; ?></textarea>
                            <?php if (isset($errores['direccion'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errores['direccion']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Teléfono -->
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" 
                                   class="form-control" 
                                   id="telefono" 
                                   name="telefono" 
                                   value="<?php echo $usuario['telefono'] ?? ''; ?>">
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="mb-4">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" 
                                   class="form-control <?php echo isset($errores['fecha_nacimiento']) ? 'is-invalid' : ''; ?>" 
                                   id="fecha_nacimiento" 
                                   name="fecha_nacimiento" 
                                   value="<?php echo $usuario['fecha_nacimiento'] ?? ''; ?>">
                            <?php if (isset($errores['fecha_nacimiento'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errores['fecha_nacimiento']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Botón de actualizar -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Actualizar Perfil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para validación del lado del cliente -->
<script>
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>

<?php require_once 'views/templates/footer.php'; ?> 