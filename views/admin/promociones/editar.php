<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Promoción</title>
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
        @media (max-width: 768px) { .dashboard-header { flex-direction: column; align-items: flex-start; gap: 1rem; padding: 1rem; } .admin-nav { padding: 0.5rem 0.5rem; gap: 0.5rem; } .main-card .card-header { padding: 1rem 1rem 0.7rem 1rem; } }
    </style>
</head>
<body>
<div class="dashboard-header">
    <div class="logo">
        <span class="fw-bold fs-4 d-flex align-items-center">
            <i class="fas fa-wine-bottle fa-2x me-2 text-primary"></i>
        </span>
        <span class="fw-bold fs-4"><i class="fas fa-percent me-2"></i>Editar Promoción</span>
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
            <span class="card-title"><i class="fas fa-percent text-danger me-2"></i>Editar Promoción</span>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label fw-semibold">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($promocion['nombre']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="descuento_porcentaje" class="form-label fw-semibold">Descuento (%)</label>
                        <input type="number" name="descuento_porcentaje" id="descuento_porcentaje" class="form-control" min="0" max="100" step="0.01" value="<?= htmlspecialchars($promocion['descuento_porcentaje']) ?>" required>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="fecha_inicio" class="form-label fw-semibold">Fecha de inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?= htmlspecialchars($promocion['fecha_inicio']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="fecha_fin" class="form-label fw-semibold">Fecha de fin</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?= htmlspecialchars($promocion['fecha_fin']) ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="3"><?= htmlspecialchars($promocion['descripcion']) ?></textarea>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="activo" name="activo" <?= $promocion['activo'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="activo">Promoción activa</label>
                </div>
                <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save"></i> Guardar cambios</button>
                <a href="<?= BASE_URL ?>admin/promociones" class="btn btn-secondary btn-lg ms-2">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<!-- Modal Confirmar Guardado -->
<div class="modal fade" id="modalConfirmarGuardar" tabindex="-1" aria-labelledby="modalConfirmarGuardarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalConfirmarGuardarLabel"><i class="fas fa-save me-2"></i>Confirmar cambios</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas guardar los cambios de esta promoción?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnConfirmarGuardar">Sí, guardar</button>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var form = document.querySelector('form');
  var modal = new bootstrap.Modal(document.getElementById('modalConfirmarGuardar'));
  var btnGuardar = document.getElementById('btnConfirmarGuardar');
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    modal.show();
  });
  btnGuardar.addEventListener('click', function() {
    form.submit();
  });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 