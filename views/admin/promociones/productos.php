<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos en Promoción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f6f9; }
        .dashboard-header { background: #23272b; color: #fff; padding: 1.2rem 2rem; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
        .dashboard-header .logo { display: flex; align-items: center; gap: 1rem; }
        .dashboard-header .user-info { display: flex; align-items: center; gap: 1.2rem; }
        .dashboard-header .user-info i { font-size: 1.7rem; }
        .dashboard-header .btn { color: #fff; border: 1px solid #fff; border-radius: 20px; padding: 0.3rem 1.1rem; }
        .dashboard-header .btn:hover { background: #fff; color: #23272b; }
        .admin-nav { background: #fff; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); padding: 0.7rem 1.2rem; margin: 2rem 0 2.5rem 0; display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .admin-nav .btn { min-width: 120px; font-size: 1rem; font-weight: 500; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); transition: transform 0.1s; }
        .admin-nav .btn:hover { transform: translateY(-2px) scale(1.04); }
        .main-card { border: none; border-radius: 18px; box-shadow: 0 2px 12px rgba(0,0,0,0.09); background: #fff; margin-bottom: 2.5rem; }
        .main-card .card-header { background: #fff; border-bottom: 1px solid #eee; padding: 1.5rem 1.5rem 1rem 1.5rem; }
        .main-card .card-title { font-weight: 700; font-size: 1.3rem; }
        .table th { font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        .table td, .table th { vertical-align: middle; }
        .btn-group .btn { padding: 0.25rem 0.5rem; }
        .btn-group .btn i { font-size: 0.875rem; }
        @media (max-width: 768px) { .dashboard-header { flex-direction: column; align-items: flex-start; gap: 1rem; padding: 1rem; } .admin-nav { padding: 0.5rem 0.5rem; gap: 0.5rem; } .main-card .card-header { padding: 1rem 1rem 0.7rem 1rem; } }
    </style>
</head>
<body>
<div class="dashboard-header">
    <div class="logo">
        <span class="fw-bold fs-4 d-flex align-items-center">
            <i class="fas fa-wine-bottle fa-2x me-2 text-primary"></i>
        </span>
        <span class="fw-bold fs-4"><i class="fas fa-percent me-2"></i>Productos en "<?= htmlspecialchars($promocion['nombre']) ?>"</span>
    </div>
    <div class="user-info">
        <i class="fas fa-user-circle"></i>
        <span><?= $_SESSION['usuario_nombre'] ?? 'Admin' ?></span>
        <a href="<?= BASE_URL ?>logout" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
    </div>
</div>
<div class="container-fluid">
    <div class="admin-nav mb-4">
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
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="card-title"><i class="fas fa-link text-info me-2"></i>Productos asociados</span>
            <a href="<?= BASE_URL ?>admin/promociones" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver a promociones</a>
        </div>
        <div class="card-body">
            <h5>Agregar producto a la promoción</h5>
            <form method="post" action="<?= BASE_URL ?>admin/promocionesAsociarProducto/<?= $promocion['promocion_id'] ?>" class="row g-3 mb-4">
                <div class="col-md-8">
                    <select name="producto_id" class="form-select" required>
                        <option value="">Seleccione un producto</option>
                        <?php foreach ($productosDisponibles as $prod): ?>
                            <option value="<?= $prod['producto_id'] ?>"> <?= htmlspecialchars($prod['nombre']) ?> (<?= number_format($prod['precio'],2) ?> €)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-info"><i class="fas fa-plus"></i> Asociar producto</button>
                </div>
            </form>
            <h5>Productos actualmente en la promoción</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Precio original</th>
                            <th>Precio con promoción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($productosAsociados)): ?>
                            <?php foreach ($productosAsociados as $prod): ?>
                                <tr>
                                    <td><?= $prod['producto_id'] ?></td>
                                    <td><?= htmlspecialchars($prod['nombre']) ?></td>
                                    <td><span class="text-decoration-line-through text-muted">€<?= number_format($prod['precio'], 2) ?></span></td>
                                    <td>
                                        <?php 
                                            $precio_desc = $prod['precio'] * (1 - $promocion['descuento_porcentaje']/100);
                                        ?>
                                        <span class="fw-bold text-success">€<?= number_format($precio_desc, 2) ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>admin/promocionesDesasociarProducto/<?= $promocion['promocion_id'] ?>/<?= $prod['producto_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Quitar este producto de la promoción?')"><i class="fas fa-unlink"></i> Quitar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">No hay productos asociados a esta promoción.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 