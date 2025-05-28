<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Promociones</title>
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
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .btn-group .btn { padding: 0.25rem 0.5rem; }
        .btn-group .btn i { font-size: 0.875rem; }
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
        <span class="fw-bold fs-4"><i class="fas fa-percent me-2"></i>Gestión de Promociones</span>
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
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
            <i class="fas fa-info-circle me-2"></i><?= htmlspecialchars($mensaje) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>
    <div class="main-card card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="card-title"><i class="fas fa-percent text-danger me-2"></i>Lista de Promociones</span>
            <a href="<?= BASE_URL ?>admin/promocionesCrear" class="btn btn-success">
                <i class="fas fa-plus"></i> Nueva Promoción
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descuento (%)</th>
                            <th>Fecha inicio</th>
                            <th>Fecha fin</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($promociones)): ?>
                            <?php foreach ($promociones as $promo): ?>
                                <tr>
                                    <td><?= $promo['promocion_id'] ?></td>
                                    <td><?= htmlspecialchars($promo['nombre']) ?></td>
                                    <td><?= number_format($promo['descuento_porcentaje'], 2) ?>%</td>
                                    <td><?= htmlspecialchars($promo['fecha_inicio']) ?></td>
                                    <td><?= htmlspecialchars($promo['fecha_fin']) ?></td>
                                    <td>
                                        <?php if ($promo['activo']): ?>
                                            <span class="badge bg-success">Activa</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactiva</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= BASE_URL ?>admin/promocionesEditar/<?= $promo['promocion_id'] ?>" class="btn btn-primary btn-sm" title="Editar"><i class="fas fa-edit"></i></a>
                                            <a href="<?= BASE_URL ?>admin/promocionesEliminar/<?= $promo['promocion_id'] ?>" class="btn btn-danger btn-sm btn-eliminar-promo" title="Eliminar" data-nombre="<?= htmlspecialchars($promo['nombre']) ?>"><i class="fas fa-trash"></i></a>
                                            <a href="<?= BASE_URL ?>admin/promociones/productos/<?= $promo['promocion_id'] ?>" class="btn btn-info btn-sm" title="Asociar productos"><i class="fas fa-link"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center">No hay promociones registradas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal Confirmar Eliminación -->
<div class="modal fade" id="modalEliminarPromo" tabindex="-1" aria-labelledby="modalEliminarPromoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalEliminarPromoLabel"><i class="fas fa-trash me-2"></i>Confirmar eliminación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar la promoción <strong id="promoNombreEliminar"></strong>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <a href="#" id="btnEliminarPromo" class="btn btn-danger">Sí, eliminar</a>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.btn-eliminar-promo').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      var nombre = this.getAttribute('data-nombre');
      var url = this.getAttribute('href');
      document.getElementById('promoNombreEliminar').textContent = nombre;
      document.getElementById('btnEliminarPromo').setAttribute('href', url);
      var modal = new bootstrap.Modal(document.getElementById('modalEliminarPromo'));
      modal.show();
    });
  });
});
</script>
</body>
</html> 