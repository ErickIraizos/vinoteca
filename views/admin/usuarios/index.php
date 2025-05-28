<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
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
        .pagination .page-link {
            padding: 0.5rem 0.75rem;
            color: #2c3e50;
        }
        .pagination .active .page-link {
            background-color: #2c3e50;
            border-color: #2c3e50;
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
        <span class="fw-bold fs-4"><i class="fas fa-users me-2"></i>Gestión de Usuarios</span>
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
    <div class="main-card card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="card-title">Lista de Usuarios</span>
            <a href="<?= BASE_URL ?>admin/usuariosCrear" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo Usuario</a>
        </div>
        <div class="card-body">
            <form action="<?= BASE_URL ?>admin/usuarios" method="GET" class="row g-3 mb-4">
                <div class="col-md-8">
                    <input type="text" class="form-control" name="q" placeholder="Buscar por nombre, email o apellido..." value="<?= htmlspecialchars($busqueda ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?= htmlspecialchars($usuario['usuario_id']) ?></td>
                                    <td><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?></td>
                                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                                    <td>
                                        <span class="badge <?= $usuario['rol'] === 'admin' ? 'bg-danger' : 'bg-info' ?>">
                                            <?= htmlspecialchars(ucfirst($usuario['rol'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?= $usuario['activo'] ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= $usuario['activo'] ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($usuario['fecha_registro'])) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= BASE_URL ?>admin/usuariosDetalle/<?= $usuario['usuario_id'] ?>" class="btn btn-sm btn-info" title="Ver detalles"><i class="fas fa-eye"></i></a>
                                            <a href="<?= BASE_URL ?>admin/usuariosEditar/<?= $usuario['usuario_id'] ?>" class="btn btn-sm btn-warning" title="Editar usuario"><i class="fas fa-edit"></i></a>
                                            <?php if ($usuario['rol'] !== 'admin'): ?>
                                            <button type="button" class="btn btn-sm btn-danger" title="Eliminar usuario" onclick="confirmarEliminacion(<?= $usuario['usuario_id'] ?>, '<?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?>')"><i class="fas fa-trash"></i></button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No se encontraron usuarios</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($paginas > 1): ?>
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $paginas; $i++): ?>
                            <li class="page-item <?= $pagina_actual == $i ? 'active' : '' ?>">
                                <a class="page-link" href="<?= BASE_URL ?>admin/usuarios?pagina=<?= $i ?><?= $busqueda ? '&q=' . urlencode($busqueda) : '' ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
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
        ¿Estás seguro de que deseas eliminar al usuario <span id="nombreUsuarioEliminar" class="fw-bold"></span>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <a href="#" class="btn btn-danger" id="btnEliminarUsuarioModal">Sí, eliminar</a>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let idUsuarioEliminar = null;
function confirmarEliminacion(id, nombre) {
    idUsuarioEliminar = id;
    document.getElementById('nombreUsuarioEliminar').textContent = nombre;
    document.getElementById('btnEliminarUsuarioModal').href = `<?= BASE_URL ?>admin/usuariosEliminar/` + id;
    const modal = new bootstrap.Modal(document.getElementById('modalConfirmarEliminar'));
    modal.show();
}
</script>
</body>
</html> 