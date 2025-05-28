<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
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
        .badge {
            padding: 0.5em 1em;
            font-weight: 500;
            border-radius: 30px;
        }
        .btn {
            border-radius: 12px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
        }
        .btn:hover {
            transform: translateY(-1px);
        }
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
            Vinoteca Online
        </span>
        <span class="fw-bold fs-4"><i class="fas fa-user-circle me-2"></i>Mi Perfil</span>
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
        <a href="<?= BASE_URL ?>admin/perfil" class="btn btn-dark"><i class="fas fa-user-circle"></i> Mi Perfil</a>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="main-card card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="card-title"><i class="fas fa-user-edit me-2"></i>Editar Perfil</span>
                    <button class="btn btn-primary" type="submit" form="formPerfil">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
                <div class="card-body">
                    <form id="formPerfil" action="<?= BASE_URL ?>admin/perfil/actualizar" method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" 
                                       value="<?= htmlspecialchars($usuario['apellido'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" 
                                   value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" 
                                   value="<?= htmlspecialchars($usuario['direccion'] ?? '') ?>">
                        </div>
                    </form>
                </div>
            </div>
            <div class="main-card card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-key me-2"></i>Cambiar Contraseña</span>
                </div>
                <div class="card-body">
                    <form id="formPassword" action="<?= BASE_URL ?>admin/perfil/cambiar-password" method="POST">
                        <div class="mb-3">
                            <label for="password_actual" class="form-label">Contraseña Actual</label>
                            <input type="password" class="form-control" id="password_actual" name="password_actual" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_nuevo" class="form-label">Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="password_nuevo" name="password_nuevo" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmar" class="form-label">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="password_confirmar" name="password_confirmar" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key"></i> Cambiar Contraseña
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="main-card card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-info-circle me-2"></i>Información de la Cuenta</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Rol</label>
                        <p class="mb-0">
                            <span class="badge bg-info">
                                <?= ucfirst($usuario['rol'] ?? 'Usuario') ?>
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Estado</label>
                        <p class="mb-0">
                            <span class="badge bg-success">Activo</span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Fecha de Registro</label>
                        <p class="mb-0">
                            <?= date('d/m/Y', strtotime($usuario['fecha_registro'] ?? 'now')) ?>
                        </p>
                    </div>
                    <div>
                        <label class="form-label text-muted">Último Acceso</label>
                        <p class="mb-0">
                            <?= date('d/m/Y H:i', strtotime($usuario['ultimo_acceso'] ?? 'now')) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('formPassword').addEventListener('submit', function(e) {
    e.preventDefault();
    const password1 = document.getElementById('password_nuevo').value;
    const password2 = document.getElementById('password_confirmar').value;
    if (password1 !== password2) {
        alert('Las contraseñas no coinciden');
        return;
    }
    this.submit();
});
</script>
</body>
</html> 