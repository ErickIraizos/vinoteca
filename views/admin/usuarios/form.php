<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($usuario) ? 'Editar Usuario' : 'Nuevo Usuario' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f6f9; }
        .dashboard-header {
            background: #23272b;
            color: #fff;
            padding: 1.2rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .dashboard-header .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .dashboard-header .logo img {
            height: 38px;
        }
        .dashboard-header .user-info {
            display: flex;
            align-items: center;
            gap: 1.2rem;
        }
        .dashboard-header .user-info i {
            font-size: 1.7rem;
        }
        .dashboard-header .btn {
            color: #fff;
            border: 1px solid #fff;
            border-radius: 20px;
            padding: 0.3rem 1.1rem;
        }
        .dashboard-header .btn:hover {
            background: #fff;
            color: #23272b;
        }
        .admin-nav {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 0.7rem 1.2rem;
            margin: 2rem 0 2.5rem 0;
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .admin-nav .btn {
            min-width: 120px;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            transition: transform 0.1s;
        }
        .admin-nav .btn:hover {
            transform: translateY(-2px) scale(1.04);
        }
        .main-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.09);
            background: #fff;
            margin-bottom: 2.5rem;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }
        .main-card .card-header {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: 1.5rem 1.5rem 1rem 1.5rem;
        }
        .main-card .card-title {
            font-weight: 700;
            font-size: 1.3rem;
        }
        .form-label { font-weight: 600; }
        .btn-primary, .btn-secondary {
            border-radius: 12px;
            font-weight: 600;
        }
        .form-check-label { font-weight: 500; }
        @media (max-width: 768px) {
            .dashboard-header { flex-direction: column; align-items: flex-start; gap: 1rem; padding: 1rem; }
            .admin-nav { padding: 0.5rem 0.5rem; gap: 0.5rem; }
            .main-card .card-header { padding: 1rem 1rem 0.7rem 1rem; }
        }
    </style>
</head>
<body>
<div class="dashboard-header">
    <div class="logo">
    <span class="fw-bold fs-4 d-flex align-items-center">
            <i class="fas fa-wine-bottle fa-2x me-2 text-primary"></i>
            
        </span>
        <span class="fw-bold fs-4"><i class="fas fa-users me-2"></i><?= isset($usuario) ? 'Editar Usuario' : 'Nuevo Usuario' ?></span>
    </div>
    <div class="user-info">
        <i class="fas fa-user-circle"></i>
        <span><?= $_SESSION['usuario_nombre'] ?? 'Admin' ?></span>
        <a href="<?= BASE_URL ?>logout" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
    </div>
</div>
<div class="container-fluid">
    <div class="admin-nav">
        <a href="<?= BASE_URL ?>admin/paneladmin" class="btn btn-secondary"><i class="fas fa-tachometer-alt"></i> Panel Admin</a>
        <a href="<?= BASE_URL ?>" class="btn btn-light"><i class="fas fa-home"></i> Inicio</a>
        <a href="<?= BASE_URL ?>admin/productos" class="btn btn-primary"><i class="fas fa-wine-bottle"></i> Productos</a>
        <a href="<?= BASE_URL ?>admin/categorias" class="btn btn-secondary"><i class="fas fa-tags"></i> Categorías</a>
        <a href="<?= BASE_URL ?>admin/pedidos" class="btn btn-success"><i class="fas fa-shopping-cart"></i> Pedidos</a>
        <a href="<?= BASE_URL ?>admin/usuarios" class="btn btn-info"><i class="fas fa-users"></i> Usuarios</a>
        <a href="<?= BASE_URL ?>admin/reportes" class="btn btn-warning text-white"><i class="fas fa-chart-line"></i> Reportes</a>
        <a href="<?= BASE_URL ?>admin/proveedores" class="btn btn-dark"><i class="fas fa-truck"></i> Proveedores</a>
        <a href="<?= BASE_URL ?>admin/promociones" class="btn btn-danger"><i class="fas fa-percent"></i> Promociones</a>
        <a href="<?= BASE_URL ?>admin/perfil" class="btn btn-dark"><i class="fas fa-user-circle"></i> Mi Perfil</a>
    </div>
    <div class="main-card card">
        <div class="card-header">
            <span class="card-title"><?= isset($usuario) ? 'Editar Usuario' : 'Nuevo Usuario' ?></span>
        </div>
        <div class="card-body">
            <form action="" method="POST" class="row g-3">
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" 
                           class="form-control <?= isset($errores['nombre']) ? 'is-invalid' : '' ?>" 
                           id="nombre" 
                           name="nombre" 
                           value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>">
                    <?php if (isset($errores['nombre'])): ?>
                        <div class="invalid-feedback"><?= $errores['nombre'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" 
                           class="form-control <?= isset($errores['apellido']) ? 'is-invalid' : '' ?>" 
                           id="apellido" 
                           name="apellido" 
                           value="<?= htmlspecialchars($usuario['apellido'] ?? '') ?>">
                    <?php if (isset($errores['apellido'])): ?>
                        <div class="invalid-feedback"><?= $errores['apellido'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" 
                           class="form-control <?= isset($errores['email']) ? 'is-invalid' : '' ?>" 
                           id="email" 
                           name="email" 
                           value="<?= htmlspecialchars($usuario['email'] ?? '') ?>">
                    <?php if (isset($errores['email'])): ?>
                        <div class="invalid-feedback"><?= $errores['email'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label for="rol" class="form-label">Rol</label>
                    <select class="form-select" id="rol" name="rol">
                        <option value="cliente" <?= ($usuario['rol'] ?? '') === 'cliente' ? 'selected' : '' ?>>Cliente</option>
                        <option value="admin" <?= ($usuario['rol'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="tel" 
                           class="form-control <?= isset($errores['telefono']) ? 'is-invalid' : '' ?>" 
                           id="telefono" 
                           name="telefono" 
                           value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
                    <?php if (isset($errores['telefono'])): ?>
                        <div class="invalid-feedback"><?= $errores['telefono'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" 
                           class="form-control <?= isset($errores['fecha_nacimiento']) ? 'is-invalid' : '' ?>" 
                           id="fecha_nacimiento" 
                           name="fecha_nacimiento" 
                           value="<?= htmlspecialchars($usuario['fecha_nacimiento'] ?? '') ?>">
                    <?php if (isset($errores['fecha_nacimiento'])): ?>
                        <div class="invalid-feedback"><?= $errores['fecha_nacimiento'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-12">
                    <label for="direccion" class="form-label">Dirección</label>
                    <textarea class="form-control <?= isset($errores['direccion']) ? 'is-invalid' : '' ?>" 
                              id="direccion" 
                              name="direccion" 
                              rows="2"><?= htmlspecialchars($usuario['direccion'] ?? '') ?></textarea>
                    <?php if (isset($errores['direccion'])): ?>
                        <div class="invalid-feedback"><?= $errores['direccion'] ?></div>
                    <?php endif; ?>
                </div>

                <?php if (empty($usuario['usuario_id'])): ?>
                <div class="col-md-6">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" 
                           class="form-control <?= isset($errores['password']) ? 'is-invalid' : '' ?>" 
                           id="password" 
                           name="password">
                    <?php if (isset($errores['password'])): ?>
                        <div class="invalid-feedback"><?= $errores['password'] ?></div>
                    <?php endif; ?>
                    <div class="form-text">La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.</div>
                </div>
                <div class="col-md-6">
                    <label for="confirmar_password" class="form-label">Confirmar contraseña</label>
                    <input type="password" 
                           class="form-control <?= isset($errores['confirmar_password']) ? 'is-invalid' : '' ?>" 
                           id="confirmar_password" 
                           name="confirmar_password">
                    <?php if (isset($errores['confirmar_password'])): ?>
                        <div class="invalid-feedback"><?= $errores['confirmar_password'] ?></div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="activo" 
                               name="activo" 
                               value="1" 
                               <?= ($usuario['activo'] ?? 0) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="activo">Usuario activo</label>
                    </div>
                </div>

                <div class="col-12">
                    <button type="button" class="btn btn-primary" id="btnGuardarUsuario">
                        <i class="fas fa-save"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- MODAL DE CONFIRMACIÓN GUARDAR -->
<div class="modal fade" id="modalConfirmarGuardar" tabindex="-1" aria-labelledby="modalConfirmarGuardarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalConfirmarGuardarLabel"><i class="fas fa-save me-2"></i>Confirmar cambios</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas guardar los cambios de este usuario?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnConfirmarGuardar">Sí, guardar</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('btnGuardarUsuario').addEventListener('click', function(e) {
        const modal = new bootstrap.Modal(document.getElementById('modalConfirmarGuardar'));
        modal.show();
    });
    document.getElementById('btnConfirmarGuardar').addEventListener('click', function() {
        document.querySelector('form').submit();
        bootstrap.Modal.getInstance(document.getElementById('modalConfirmarGuardar')).hide();
    });
});
</script>
</body>
</html> 