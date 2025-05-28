<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($producto) ? 'Editar Producto' : 'Nuevo Producto'; ?></title>
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
        .img-thumbnail {
            border-radius: 10px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
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
        <span class="fw-bold fs-4"><i class="fas fa-wine-bottle me-2"></i><?php echo isset($producto) ? 'Editar Producto' : 'Nuevo Producto'; ?></span>
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
            <span class="card-title"><?php echo isset($producto) ? 'Editar Producto' : 'Nuevo Producto'; ?></span>
        </div>
        <div class="card-body">
            <form action="<?php echo BASE_URL; ?>admin/<?php echo isset($producto) ? 'productoForm/'.$producto['producto_id'] : 'productoForm'; ?>" method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo isset($producto) ? htmlspecialchars($producto['nombre']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="categoria_id" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria_id" name="categoria_id" required>
                            <option value="">Seleccionar categoría</option>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?php echo $categoria['categoria_id']; ?>" <?php echo (isset($producto) && $producto['categoria_id'] == $categoria['categoria_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($categoria['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="precio" class="form-label">Precio (€)</label>
                        <input type="number" class="form-control" id="precio" name="precio" step="0.01" value="<?php echo isset($producto) ? number_format($producto['precio'], 2) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock" value="<?php echo isset($producto) ? $producto['stock'] : ''; ?>" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="grado_alcoholico" class="form-label">Grado Alcohólico</label>
                        <input type="number" class="form-control" id="grado_alcoholico" name="grado_alcoholico" step="0.1" value="<?php echo isset($producto) ? $producto['grado_alcoholico'] : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="pais_origen" class="form-label">País de Origen</label>
                        <input type="text" class="form-control" id="pais_origen" name="pais_origen" value="<?php echo isset($producto) ? htmlspecialchars($producto['pais_origen']) : ''; ?>" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="marca" class="form-label">Marca</label>
                        <input type="text" class="form-control" id="marca" name="marca" value="<?php echo isset($producto) ? htmlspecialchars($producto['marca']) : ''; ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?php echo isset($producto) ? htmlspecialchars($producto['descripcion']) : ''; ?></textarea>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="imagen_url" class="form-label">URL de Imagen Principal</label>
                        <input type="text" class="form-control" id="imagen_url" name="imagen_url" value="<?php echo isset($producto) ? htmlspecialchars($producto['imagen_url']) : ''; ?>" placeholder="https://..." required>
                        <small class="form-text text-muted">Pega la URL de la imagen principal (JPG, PNG, GIF).</small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="destacado" name="destacado" value="1" <?php echo (isset($producto) && $producto['destacado']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="destacado">Producto Destacado</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1" <?php echo (isset($producto) && $producto['activo']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="activo">Producto Activo</label>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?php echo BASE_URL; ?>admin/productos" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <?php echo isset($producto) ? 'Actualizar Producto' : 'Crear Producto'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
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
        ¿Estás seguro de que deseas guardar los cambios de este producto?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarConfirmado">Guardar</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const form = document.querySelector('form');
let submitIntent = false;
form.addEventListener('submit', function(e) {
    if (!submitIntent) {
        e.preventDefault();
        const modal = new bootstrap.Modal(document.getElementById('modalConfirmarGuardar'));
        modal.show();
    }
});
document.getElementById('btnGuardarConfirmado').onclick = function() {
    submitIntent = true;
    form.submit();
};
</script>
</body>
</html> 