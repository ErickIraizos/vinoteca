<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías</title>
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
        .main-card .btn-primary {
            border-radius: 12px;
            font-weight: 600;
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
        .img-thumbnail {
            border-radius: 10px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .badge {
            padding: 0.5em 1em;
            font-weight: 500;
            border-radius: 30px;
        }
        .btn-group .btn {
            padding: 0.25rem 0.5rem;
        }
        .btn-group .btn i {
            font-size: 0.875rem;
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
            
        </span>
        <span class="fw-bold fs-4"><i class="fas fa-tags me-2"></i>Gestión de Categorías</span>
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
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <span class="card-title">Lista de Categorías</span>
            </div>
            <div>
                <a href="<?= BASE_URL ?>admin/categoriaForm" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Categoría
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Productos</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($categorias)): ?>
                            <?php foreach ($categorias as $categoria): ?>
                                <tr>
                                    <td><?= $categoria['categoria_id'] ?? '' ?></td>
                                    <td>
                                        <img src="<?= $categoria['imagen_url'] ?? BASE_URL . 'public/img/no-image.jpg' ?>" 
                                             alt="<?= htmlspecialchars($categoria['nombre']) ?>" 
                                             class="img-thumbnail" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    </td>
                                    <td><?= htmlspecialchars($categoria['nombre']) ?></td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" title="<?= htmlspecialchars($categoria['descripcion']) ?>">
                                            <?= htmlspecialchars($categoria['descripcion']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= $categoria['total_productos'] ?? 0 ?> productos
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?= $categoria['activa'] ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $categoria['activa'] ? 'Activa' : 'Inactiva' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= BASE_URL ?>admin/categoriaForm/<?= $categoria['categoria_id'] ?? '' ?>" 
                                               class="btn btn-sm btn-info" 
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="confirmarEliminacion(<?= $categoria['categoria_id'] ?? 0 ?>)" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-muted mb-0">No se encontraron categorías</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- MODAL DE CONFIRMACIÓN ELIMINAR -->
<div class="modal fade" id="modalConfirmarEliminar" tabindex="-1" aria-labelledby="modalConfirmarEliminarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalConfirmarEliminarLabel"><i class="fas fa-trash me-2"></i>Confirmar eliminación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar esta categoría? Se eliminarán también todos los productos asociados.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="btnEliminarConfirmado">Eliminar</button>
      </div>
    </div>
  </div>
</div>
<!-- TOAST DE ALERTA -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
  <div id="toastAlerta" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="toastAlertaMsg">
        <!-- Mensaje dinámico -->
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let categoriaAEliminar = null;
function confirmarEliminacion(id) {
    categoriaAEliminar = id;
    const modal = new bootstrap.Modal(document.getElementById('modalConfirmarEliminar'));
    modal.show();
}
document.getElementById('btnEliminarConfirmado').onclick = function() {
    if (categoriaAEliminar) {
        window.location.href = `<?= BASE_URL ?>admin/eliminarCategoria/${categoriaAEliminar}`;
    }
};
// Función para mostrar toast de alerta
function mostrarToast(mensaje, tipo = 'primary') {
    const toast = document.getElementById('toastAlerta');
    const toastMsg = document.getElementById('toastAlertaMsg');
    toast.className = `toast align-items-center text-bg-${tipo} border-0`;
    toastMsg.textContent = mensaje;
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}
// Para mostrar un mensaje tras eliminar:
// mostrarToast('Categoría eliminada correctamente', 'success');
// mostrarToast('Error al eliminar la categoría', 'danger');
</script>
</body>
</html> 