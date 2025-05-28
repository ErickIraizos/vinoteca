<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
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
        .stats-row {
            margin-bottom: 2.5rem;
        }
        .stat-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.09);
            display: flex;
            align-items: center;
            padding: 2rem 1.5rem;
            background: #fff;
            margin-bottom: 1.5rem;
            min-height: 120px;
        }
        .stat-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin-right: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .stat-products { background: #8B4513; color: #fff; }
        .stat-orders { background: #198754; color: #fff; }
        .stat-users { background: #0dcaf0; color: #fff; }
        .stat-income { background: #ffc107; color: #fff; }
        .stat-label { font-size: 0.95rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #888; }
        .stat-value { font-size: 2.1rem; font-weight: 700; color: #23272b; }
        .dashboard-section {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 2rem 1.5rem 1.5rem 1.5rem;
            margin-bottom: 2rem;
        }
        .dashboard-section h5 {
            font-weight: 700;
            margin-bottom: 1.2rem;
        }
        .table thead th { background: #f8f9fa; }
        .badge { padding: 0.5em 1em; font-weight: 500; border-radius: 30px; }
        .list-group-item { border: none; padding: 1rem 0; border-bottom: 1px solid #eee; }
        .list-group-item:last-child { border-bottom: none; }
        @media (max-width: 992px) {
            .stat-card { flex-direction: column; align-items: flex-start; gap: 0.7rem; min-height: 100px; }
            .stat-icon { margin-right: 0; margin-bottom: 0.7rem; }
        }
        @media (max-width: 768px) {
            .dashboard-header { flex-direction: column; align-items: flex-start; gap: 1rem; padding: 1rem; }
            .admin-nav { padding: 0.5rem 0.5rem; gap: 0.5rem; }
            .stat-card { padding: 1.2rem 1rem; }
            .dashboard-section { padding: 1.2rem 0.7rem; }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php $stats = $stats ?? []; ?>
<div class="dashboard-header">
    <div class="logo">
        <span class="fw-bold fs-4 d-flex align-items-center">
            <i class="fas fa-wine-bottle fa-2x me-2 text-primary"></i>
            Vinoteca Online
        </span>
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
    <div class="row stats-row">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon stat-products"><i class="fas fa-wine-bottle"></i></div>
                <div>
                    <div class="stat-label">Total Productos</div>
                    <div class="stat-value"><?= number_format($stats['total_productos'] ?? 0) ?></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon stat-orders"><i class="fas fa-shopping-cart"></i></div>
                <div>
                    <div class="stat-label">Total Pedidos</div>
                    <div class="stat-value"><?= number_format($stats['total_pedidos'] ?? 0) ?></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon stat-users"><i class="fas fa-users"></i></div>
                <div>
                    <div class="stat-label">Total Usuarios</div>
                    <div class="stat-value"><?= number_format($stats['total_usuarios'] ?? 0) ?></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon stat-income"><i class="fas fa-euro-sign"></i></div>
                <div>
                    <div class="stat-label">Ingresos Totales</div>
                    <div class="stat-value">€<?= number_format($stats['ingresos_totales'] ?? 0, 2) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8">
            <div class="dashboard-section mb-4">
                <h5><i class="fas fa-chart-bar me-2"></i>Ventas Recientes</h5>
                <canvas id="ventasChart" height="120"></canvas>
                <div class="table-responsive mt-4">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($stats['ventas_recientes'])): ?>
                                <?php foreach ($stats['ventas_recientes'] as $venta): ?>
                                    <tr>
                                        <td>#<?= $venta['pedido_id'] ?></td>
                                        <td><?= htmlspecialchars($venta['nombre_cliente']) ?></td>
                                        <td>€<?= number_format($venta['total'], 2) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $venta['estado'] === 'completado' ? 'success' : 'warning' ?>">
                                                <?= ucfirst($venta['estado']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($venta['fecha_pedido'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No hay ventas recientes</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="dashboard-section mb-4">
                <h5><i class="fas fa-star me-2"></i>Productos Populares</h5>
                <?php if (!empty($stats['productos_populares'])): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($stats['productos_populares'] as $producto): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?= htmlspecialchars($producto['nombre']) ?></h6>
                                        <small class="text-muted">
                                            <?= number_format($producto['ventas'] ?? 0) ?> ventas
                                        </small>
                                    </div>
                                    <span class="badge bg-success rounded-pill">
                                        €<?= number_format($producto['total'] ?? 0, 2) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted my-3">No hay datos disponibles</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
// Datos para la gráfica de ventas recientes
<?php
$labels = [];
$data = [];
if (!empty($stats['ventas_recientes'])) {
    foreach ($stats['ventas_recientes'] as $venta) {
        $labels[] = date('d/m', strtotime($venta['fecha_pedido']));
        $data[] = $venta['total'];
    }
}
?>
const ctx = document.getElementById('ventasChart').getContext('2d');
const ventasChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Ventas (€)',
            data: <?= json_encode($data) ?>,
            backgroundColor: '#198754',
            borderRadius: 8,
            maxBarThickness: 32
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            title: { display: false }
        },
        scales: {
            x: { grid: { display: false } },
            y: { beginAtZero: true, grid: { color: '#eee' } }
        }
    }
});
</script>
</body>
</html> 