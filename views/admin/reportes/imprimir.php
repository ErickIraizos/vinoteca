<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte - <?= htmlspecialchars($title) ?></title>
    <style>
        @media print {
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
                color: #000;
            }
            .no-print {
                display: none !important;
            }
        }
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #ddd;
        }
        .header h1 {
            margin: 0;
            color: #2c3e50;
        }
        .header p {
            margin: 10px 0;
            color: #7f8c8d;
        }
        .stats-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }
        .stat-box {
            flex: 1;
            min-width: 200px;
            margin: 10px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            text-align: center;
        }
        .stat-box h3 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }
        .stat-box p {
            margin: 5px 0 0;
            color: #7f8c8d;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            color: #2c3e50;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .no-print {
            margin: 20px 0;
            text-align: center;
        }
        .btn {
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de <?= ucfirst($tipo) ?></h1>
        <p>Período: <?= date('d/m/Y', strtotime($desde)) ?> - <?= date('d/m/Y', strtotime($hasta)) ?></p>
    </div>

    <div class="stats-container">
        <div class="stat-box">
            <h3><?= number_format($stats['ventas_totales'], 2, ',', '.') ?>€</h3>
            <p>Ventas Totales</p>
        </div>
        <div class="stat-box">
            <h3><?= $stats['pedidos_completados'] ?></h3>
            <p>Pedidos Completados</p>
        </div>
        <div class="stat-box">
            <h3><?= $stats['productos_vendidos'] ?></h3>
            <p>Productos Vendidos</p>
        </div>
        <div class="stat-box">
            <h3><?= $stats['nuevos_clientes'] ?></h3>
            <p>Nuevos Clientes</p>
        </div>
    </div>

    <table>
        <?php if ($tipo === 'ventas'): ?>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Total de Pedidos</th>
                    <th>Total Ventas (€)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($datos as $fila): ?>
                    <tr>
                        <td><?= $fila['fecha'] ?></td>
                        <td><?= $fila['total_pedidos'] ?></td>
                        <td><?= number_format($fila['total_ventas'], 2, ',', '.') ?>€</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php elseif ($tipo === 'productos'): ?>
            <thead>
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
                        <td><?= number_format($fila['total_ventas'], 2, ',', '.') ?>€</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php else: ?>
            <thead>
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
                        <td><?= number_format($fila['total_compras'], 2, ',', '.') ?>€</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php endif; ?>
    </table>

    <div class="no-print">
        <button onclick="window.print()" class="btn">Imprimir</button>
        <a href="<?= BASE_URL ?>admin/reportes" class="btn">Volver</a>
    </div>
</body>
</html> 