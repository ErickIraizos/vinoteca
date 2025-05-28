<?php require_once 'views/templates/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h1 class="h3 mb-4 text-center">Crear cuenta</h1>

                    <form action="<?php echo BASE_URL; ?>auth/registro" method="POST" class="needs-validation" novalidate>
                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" 
                                   class="form-control <?php echo isset($errores['nombre']) ? 'is-invalid' : ''; ?>" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="<?php echo $old['nombre'] ?? ''; ?>" 
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
                                   value="<?php echo $old['apellido'] ?? ''; ?>" 
                                   required>
                            <?php if (isset($errores['apellido'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errores['apellido']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" 
                                   class="form-control <?php echo isset($errores['email']) ? 'is-invalid' : ''; ?>" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo $old['email'] ?? ''; ?>" 
                                   required>
                            <?php if (isset($errores['email'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errores['email']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Dirección -->
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <textarea class="form-control <?php echo isset($errores['direccion']) ? 'is-invalid' : ''; ?>" 
                                      id="direccion" 
                                      name="direccion" 
                                      rows="2"><?php echo $old['direccion'] ?? ''; ?></textarea>
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
                                   class="form-control <?php echo isset($errores['telefono']) ? 'is-invalid' : ''; ?>" 
                                   id="telefono" 
                                   name="telefono" 
                                   value="<?php echo $old['telefono'] ?? ''; ?>">
                            <?php if (isset($errores['telefono'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errores['telefono']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="mb-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" 
                                   class="form-control <?php echo isset($errores['fecha_nacimiento']) ? 'is-invalid' : ''; ?>" 
                                   id="fecha_nacimiento" 
                                   name="fecha_nacimiento" 
                                   value="<?php echo $old['fecha_nacimiento'] ?? ''; ?>">
                            <?php if (isset($errores['fecha_nacimiento'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errores['fecha_nacimiento']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Contraseña -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" 
                                   class="form-control <?php echo isset($errores['password']) ? 'is-invalid' : ''; ?>" 
                                   id="password" 
                                   name="password" 
                                   required>
                            <?php if (isset($errores['password'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errores['password']; ?>
                                </div>
                            <?php endif; ?>
                            <div class="form-text">
                                La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.
                            </div>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="mb-4">
                            <label for="confirmar_password" class="form-label">Confirmar contraseña</label>
                            <input type="password" 
                                   class="form-control <?php echo isset($errores['confirmar_password']) ? 'is-invalid' : ''; ?>" 
                                   id="confirmar_password" 
                                   name="confirmar_password" 
                                   required>
                            <?php if (isset($errores['confirmar_password'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errores['confirmar_password']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Botón de registro -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Crear cuenta</button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-0">¿Ya tienes cuenta? <a href="<?php echo BASE_URL; ?>auth/login">Iniciar sesión</a></p>
                    </div>
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