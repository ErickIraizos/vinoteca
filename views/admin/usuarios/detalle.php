<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuario #<?= $usuario['usuario_id'] ?></title>
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
        .titulo-pagina {
            font-size: 2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }
        @media (max-width: 768px) {
            .main-card { margin: 1rem; }
            .titulo-pagina { font-size: 1.3rem; margin: 1.5rem 0 1rem 0; }
            .dashboard-header { flex-direction: column; align-items: flex-start; gap: 1rem; padding: 1rem; }
            .admin-nav { padding: 0.5rem 0.5rem; gap: 0.5rem; }
        }
    </style>
</head>
<body>
<div class="dashboard-header">
    <div class="logo">
    <span class="fw-bold fs-4 d-flex align-items-center">
            <i class="fas fa-wine-bottle fa-2x me-2 text-primary"></i>
           
        </span>
        <span class="fw-bold fs-4"><i class="fas fa-users me-2"></i>Usuario #<?= $usuario['usuario_id'] ?></span>
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
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="main-card card">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 border-end">
                            <h6 class="text-muted mb-3">Información Personal</h6>
                            <ul class="list-unstyled mb-0">
                                <li><strong>Nombre Completo:</strong> <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?></li>
                                <li><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></li>
                                <li><strong>Teléfono:</strong> <?= htmlspecialchars($usuario['telefono'] ?? 'No especificado') ?></li>
                                <li><strong>Dirección:</strong> <?= htmlspecialchars($usuario['direccion'] ?? 'No especificada') ?></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Información de la Cuenta</h6>
                            <ul class="list-unstyled mb-0">
                                <li><strong>ID:</strong> <?= htmlspecialchars($usuario['usuario_id']) ?></li>
                                <li><strong>Rol:</strong> <span class="badge <?= $usuario['rol'] === 'admin' ? 'bg-danger' : 'bg-info' ?>"><?= htmlspecialchars(ucfirst($usuario['rol'])) ?></span></li>
                                <li><strong>Estado:</strong> <span class="badge <?= $usuario['activo'] ? 'bg-success' : 'bg-secondary' ?>"><?= $usuario['activo'] ? 'Activo' : 'Inactivo' ?></span></li>
                                <li><strong>Fecha de Registro:</strong> <?= date('d/m/Y', strtotime($usuario['fecha_registro'])) ?></li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="<?= BASE_URL ?>admin/usuarios" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 