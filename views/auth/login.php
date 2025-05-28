<?php require_once 'views/templates/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h1 class="h3 text-center mb-4">Iniciar Sesión</h1>

                    <?php if (SessionHelper::getFlash('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo SessionHelper::getFlash('error'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (SessionHelper::getFlash('success')): ?>
                        <div class="alert alert-success">
                            <?php echo SessionHelper::getFlash('success'); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo BASE_URL; ?>auth/login" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="recordar" name="recordar">
                            <label class="form-check-label" for="recordar">Recordarme</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-2">
                            <a href="<?php echo BASE_URL; ?>auth/recuperar" class="text-decoration-none">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </p>
                        <p class="mb-0">
                            ¿No tienes cuenta? 
                            <a href="<?php echo BASE_URL; ?>auth/registro" class="text-decoration-none">
                                Regístrate aquí
                            </a>
                        </p>
                    </div>

                    <div class="divider my-4">
                        <span class="divider-text">o</span>
                    </div>

                    <!-- Botones de redes sociales -->
                    <div class="social-login">
                        <a href="<?php echo BASE_URL; ?>auth/facebook" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fab fa-facebook me-2"></i> Continuar con Facebook
                        </a>
                        <a href="<?php echo BASE_URL; ?>auth/google" class="btn btn-outline-danger w-100">
                            <i class="fab fa-google me-2"></i> Continuar con Google
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.divider {
    position: relative;
    text-align: center;
    height: 1px;
    background: #dee2e6;
}

.divider-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 0 1rem;
    color: #6c757d;
}

.social-login .btn {
    text-align: left;
    padding: 0.5rem 1rem;
}
</style>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const icon = event.target.closest('button').querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<?php require_once 'views/templates/footer.php'; ?> 