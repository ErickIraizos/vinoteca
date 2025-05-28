<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white <?= strpos($_SERVER['REQUEST_URI'], 'admin/index') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>admin">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white <?= strpos($_SERVER['REQUEST_URI'], 'admin/productos') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>admin/productos">
                            <i class="fas fa-wine-bottle me-2"></i>
                            Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white <?= strpos($_SERVER['REQUEST_URI'], 'admin/categorias') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>admin/categorias">
                            <i class="fas fa-tags me-2"></i>
                            Categorías
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white <?= strpos($_SERVER['REQUEST_URI'], 'admin/pedidos') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>admin/pedidos">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Pedidos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white <?= strpos($_SERVER['REQUEST_URI'], 'admin/usuarios') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>admin/usuarios">
                            <i class="fas fa-users me-2"></i>
                            Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white <?= strpos($_SERVER['REQUEST_URI'], 'admin/reportes') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>admin/reportes">
                            <i class="fas fa-chart-bar me-2"></i>
                            Reportes
                        </a>
                    </li>
                </ul>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Configuración</span>
                </h6>
                <ul class="nav flex-column mb-2">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= BASE_URL ?>admin/perfil">
                            <i class="fas fa-user-cog me-2"></i>
                            Mi Perfil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= BASE_URL ?>auth/logout">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4"> 