<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes y Estadísticas</title>
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
        .card.shadow-sm, .main-card {
            box-shadow: 0 2px 12px rgba(0,0,0,0.09) !important;
        }
        @media (max-width: 768px) {
            .dashboard-header { flex-direction: column; align-items: flex-start; gap: 1rem; padding: 1rem; }
            .admin-nav { padding: 0.5rem 0.5rem; gap: 0.5rem; }
            .main-card .card-header { padding: 1rem 1rem 0.7rem 1rem; }
        }
        .ventas-modern-chart {
            background: linear-gradient(135deg, #f8fafc 60%, #e3e9f7 100%);
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(78,115,223,0.07);
            padding: 1.5rem 1.5rem 1rem 1.5rem;
            margin-bottom: 2rem;
            max-height: 460px;
            min-height: 460px;
            height: 460px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #ventasCategoriaChart {
            max-height: 440px !important;
            min-height: 440px !important;
            height: 440px !important;
        }
    </style>
</head>
<body>
<div class="dashboard-header">
    <div class="logo">
    <span class="fw-bold fs-4 d-flex align-items-center">
            <i class="fas fa-wine-bottle fa-2x me-2 text-primary"></i>
            
        </span>
        <span class="fw-bold fs-4"><i class="fas fa-chart-line me-2"></i>Reportes y Estadísticas</span>
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
   

    <div class="row">
        <!-- Tarjetas de resumen -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Ventas Totales</div>
                            <div class="h5 mb-0 font-weight-bold">€<?= number_format($stats['ventas_totales'], 2) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pedidos Entregados</div>
                            <div class="h5 mb-0 font-weight-bold"><?= $stats['pedidos_completados'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Productos Vendidos</div>
                            <div class="h5 mb-0 font-weight-bold"><?= $stats['productos_vendidos'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Clientes Nuevos</div>
                            <div class="h5 mb-0 font-weight-bold"><?= $stats['nuevos_clientes'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row">
        <!-- Gráfico de Ventas Mensuales -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>Ventas Mensuales
                    </h6>
                    <div class="dropdown no-arrow">
                        <button class="btn btn-link btn-sm" type="button" id="ventasDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="ventasDropdown">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Descargar Reporte</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Imprimir Gráfico</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="ventas-modern-chart">
                        <canvas id="ventasCategoriaChart" height="160"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de Productos Más Vendidos -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Productos Más Vendidos
                    </h6>
                    <div class="dropdown no-arrow">
                        <button class="btn btn-link btn-sm" type="button" id="productosDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="productosDropdown">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Descargar Datos</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Imprimir Gráfico</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:400px; width:100%">
                        <canvas id="productosChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Últimas Ventas -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold">Últimas Ventas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Pedido ID</th>
                            <th>Cliente</th>
                            <th>Productos</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimas_ventas as $venta): ?>
                        <tr>
                            <td>#<?= $venta['pedido_id'] ?></td>
                            <td><?= htmlspecialchars($venta['cliente_nombre']) ?></td>
                            <td><?= $venta['total_productos'] ?> items</td>
                            <td>€<?= number_format($venta['total'], 2) ?></td>
                            <td>
                                <span class="badge bg-<?= $venta['estado_color'] ?>">
                                    <?= htmlspecialchars($venta['estado']) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="tipo" class="form-label">Tipo de Reporte</label>
                        <select name="tipo" id="tipo" class="form-select">
                            <option value="ventas" <?= $tipo === 'ventas' ? 'selected' : '' ?>>Ventas</option>
                            <option value="productos" <?= $tipo === 'productos' ? 'selected' : '' ?>>Productos más vendidos</option>
                            <option value="usuarios" <?= $tipo === 'usuarios' ? 'selected' : '' ?>>Usuarios que más compran</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="desde" class="form-label">Desde</label>
                        <input type="date" name="desde" id="desde" class="form-control" value="<?= htmlspecialchars($desde) ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="hasta" class="form-label">Hasta</label>
                        <input type="date" name="hasta" id="hasta" class="form-control" value="<?= htmlspecialchars($hasta) ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Generar Reporte
                        </button>
                    </div>
                    <div class="col-md-3">
                        <div class="btn-group w-100">
                            <!-- <a href="<?= BASE_URL ?>admin/exportarReporte?tipo=<?= $tipo ?>&desde=<?= $desde ?>&hasta=<?= $hasta ?>&formato=csv" 
                               class="btn btn-success">
                                <i class="fas fa-file-excel"></i> CSV
                            </a> -->
                            <a href="<?= BASE_URL ?>admin/exportarReporte?tipo=<?= $tipo ?>&desde=<?= $desde ?>&hasta=<?= $hasta ?>&formato=pdf" 
                               class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                            <a href="<?= BASE_URL ?>admin/imprimirReporte?tipo=<?= $tipo ?>&desde=<?= $desde ?>&hasta=<?= $hasta ?>" 
                               class="btn btn-info" target="_blank">
                                <i class="fas fa-print"></i> Imprimir
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($tipo === 'ventas'): ?>
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">Reporte de Ventas</h6></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Total de Pedidos</th>
                                    <th>Total Ventas (€)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($datos as $fila): ?>
                                <tr>
                                    <td><?= htmlspecialchars($fila['fecha']) ?></td>
                                    <td><?= $fila['total_pedidos'] ?></td>
                                    <td>€<?= number_format($fila['total_ventas'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php elseif ($tipo === 'productos'): ?>
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">Productos más vendidos</h6></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad Vendida</th>
                                    <th>Total Ventas (€)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($datos as $fila): ?>
                                <tr>
                                    <td><?= htmlspecialchars($fila['nombre']) ?></td>
                                    <td><?= $fila['cantidad_total'] ?></td>
                                    <td>€<?= number_format($fila['total_ventas'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php elseif ($tipo === 'usuarios'): ?>
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">Usuarios que más compran</h6></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Total Pedidos</th>
                                    <th>Total Compras (€)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($datos as $fila): ?>
                                <tr>
                                    <td><?= htmlspecialchars($fila['nombre']) ?></td>
                                    <td><?= htmlspecialchars($fila['email']) ?></td>
                                    <td><?= $fila['total_pedidos'] ?></td>
                                    <td>€<?= number_format($fila['total_compras'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Aquí van los scripts de Chart.js y cualquier otro JS necesario para los gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuración común para los gráficos
    Chart.defaults.font.family = "'Nunito', 'Segoe UI', arial";
    Chart.defaults.font.size = 13;
    Chart.defaults.plugins.legend.labels.usePointStyle = true;

    // Datos para el gráfico de ventas mensuales
    const ventasData = {
        labels: <?= json_encode($stats['meses']) ?>,
        datasets: [{
            label: 'Ventas €',
            data: <?= json_encode($stats['ventas_por_mes']) ?>,
            fill: true,
            backgroundColor: 'rgba(78, 115, 223, 0.05)',
            borderColor: 'rgba(78, 115, 223, 1)',
            pointBackgroundColor: 'rgba(78, 115, 223, 1)',
            pointBorderColor: '#fff',
            pointHoverRadius: 6,
            pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
            pointHoverBorderColor: '#fff',
            tension: 0.3
        }]
    };

    // Datos para el gráfico de productos más vendidos
    const productosData = {
        labels: <?= json_encode(array_column($stats['productos_populares'], 'nombre')) ?>,
        datasets: [{
            data: <?= json_encode(array_column($stats['productos_populares'], 'cantidad')) ?>,
            backgroundColor: [
                'rgba(78, 115, 223, 0.8)',
                'rgba(28, 200, 138, 0.8)',
                'rgba(54, 185, 204, 0.8)',
                'rgba(246, 194, 62, 0.8)',
                'rgba(231, 74, 59, 0.8)'
            ],
            borderColor: '#ffffff',
            borderWidth: 2
        }]
    };

    // Configuración del gráfico de ventas
    new Chart(document.getElementById('ventasCategoriaChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($stats['ventas_por_categoria'], 'categoria')) ?>,
            datasets: [{
                data: <?= json_encode(array_map(function($v){return (float)$v['total_ventas'];}, $stats['ventas_por_categoria'])) ?>,
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#fd7e14', '#20c997', '#6f42c1', '#ff6384'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        color: '#23272b',
                        font: { size: 15, weight: 'bold' }
                    }
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#4e73df',
                    bodyColor: '#23272b',
                    borderColor: '#4e73df',
                    borderWidth: 1,
                    padding: 14,
                    callbacks: {
                        label: function(context) {
                            return ` ${context.label}: €${context.parsed.toLocaleString()}`;
                        }
                    }
                }
            },
            animation: {
                animateRotate: true,
                animateScale: true
            }
        }
    });

    // Configuración del gráfico de productos (barras horizontales)
    new Chart(document.getElementById('productosChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($stats['productos_populares'], 'nombre')) ?>,
            datasets: [{
                label: 'Cantidad Vendida',
                data: <?= json_encode(array_column($stats['productos_populares'], 'cantidad')) ?>,
                backgroundColor: [
                    'rgba(78, 115, 223, 0.8)',
                    'rgba(28, 200, 138, 0.8)',
                    'rgba(54, 185, 204, 0.8)',
                    'rgba(246, 194, 62, 0.8)',
                    'rgba(231, 74, 59, 0.8)'
                ],
                borderColor: '#ffffff',
                borderWidth: 2,
                borderRadius: 8,
                maxBarThickness: 38
            }]
        },
        options: {
            indexAxis: 'y', // Barras horizontales
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#6e707e',
                    titleFont: { size: 14, weight: 'bold' },
                    bodyColor: '#858796',
                    bodyFont: { size: 13 },
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    padding: 12
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)', drawBorder: false },
                    ticks: { color: '#23272b', font: { weight: 'bold' } }
                },
                y: {
                    grid: { display: false },
                    ticks: { color: '#23272b', font: { weight: 'bold' } }
                }
            }
        }
    });
});
</script>
</body>
</html> 