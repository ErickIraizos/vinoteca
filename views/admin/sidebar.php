<?php
$current_page = basename($_SERVER['REQUEST_URI']);
?>

<div class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-header">
        <img src="<?php echo BASE_URL; ?>assets/img/logo-small.png" alt="Logo" class="sidebar-logo">
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page === 'admin' ? 'active' : ''; ?>" 
               href="<?php echo BASE_URL; ?>admin">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo strpos($current_page, 'productos') !== false ? 'active' : ''; ?>" 
               href="<?php echo BASE_URL; ?>admin/productos">
                <i class="fas fa-wine-bottle"></i>
                <span>Productos</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo strpos($current_page, 'categorias') !== false ? 'active' : ''; ?>" 
               href="<?php echo BASE_URL; ?>admin/categorias">
                <i class="fas fa-tags"></i>
                <span>Categorías</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo strpos($current_page, 'pedidos') !== false ? 'active' : ''; ?>" 
               href="<?php echo BASE_URL; ?>admin/pedidos">
                <i class="fas fa-shopping-cart"></i>
                <span>Pedidos</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo strpos($current_page, 'usuarios') !== false ? 'active' : ''; ?>" 
               href="<?php echo BASE_URL; ?>admin/usuarios">
                <i class="fas fa-users"></i>
                <span>Usuarios</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo strpos($current_page, 'reportes') !== false ? 'active' : ''; ?>" 
               href="<?php echo BASE_URL; ?>admin/reportes">
                <i class="fas fa-chart-line"></i>
                <span>Reportes</span>
            </a>
        </li>
        <li class="nav-divider"></li>
        <li class="nav-item">
            <a class="nav-link <?php echo strpos($current_page, 'perfil') !== false ? 'active' : ''; ?>" 
               href="<?php echo BASE_URL; ?>admin/perfil">
                <i class="fas fa-user-circle"></i>
                <span>Mi Perfil</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>auth/logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </a>
        </li>
    </ul>
</div>

<div class="sidebar-overlay" onclick="toggleSidebar()"></div>

<button class="sidebar-toggle" onclick="toggleSidebar()" title="Alternar menú">
    <i class="fas fa-bars"></i>
</button>

<style>
.admin-sidebar {
    width: 260px;
    background: #2c3e50;
    position: fixed;
    left: -260px;
    top: 0;
    bottom: 0;
    transition: all 0.3s ease;
    z-index: 1040;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    overflow-y: auto;
}

.admin-sidebar.show {
    left: 0;
}

.sidebar-header {
    padding: 20px;
    text-align: center;
    background: #243342;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.sidebar-logo {
    max-width: 120px;
    height: auto;
}

.nav-item {
    margin: 5px 15px;
}

.nav-link {
    color: #ecf0f1;
    padding: 12px 20px;
    border-radius: 5px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    text-decoration: none;
}

.nav-link:hover {
    background: rgba(255,255,255,0.1);
    color: #fff;
    transform: translateX(5px);
}

.nav-link.active {
    background: #3498db;
    color: #fff;
}

.nav-link i {
    width: 20px;
    text-align: center;
    margin-right: 10px;
    font-size: 1.1rem;
}

.nav-link span {
    font-size: 0.95rem;
}

.nav-divider {
    height: 1px;
    background: rgba(255,255,255,0.1);
    margin: 15px;
}

/* Overlay para móviles */
.sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1030;
}

.sidebar-overlay.show {
    display: block;
}

/* Botón toggle del sidebar */
.sidebar-toggle {
    position: fixed;
    top: 20px;
    left: 20px;
    width: 40px;
    height: 40px;
    background: #3498db;
    border: none;
    border-radius: 50%;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 1050;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.sidebar-toggle:hover {
    background: #2980b9;
    transform: scale(1.1);
}

.sidebar-toggle i {
    font-size: 1.2rem;
    transition: transform 0.3s ease;
}

.admin-sidebar.show + .sidebar-toggle i {
    transform: rotate(180deg);
}

/* Ajuste del contenido principal */
.main-content {
    transition: margin-left 0.3s ease;
    padding: 20px;
}

.main-content.shifted {
    margin-left: 260px;
}

@media (max-width: 768px) {
    .main-content.shifted {
        margin-left: 0;
    }
}
</style>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const mainContent = document.querySelector('.main-content');

    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
    mainContent.classList.toggle('shifted');
}

// Cerrar sidebar al cambiar el tamaño de la ventana en móviles
window.addEventListener('resize', function() {
    if (window.innerWidth <= 768) {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        const mainContent = document.querySelector('.main-content');
        
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        mainContent.classList.remove('shifted');
    }
});
</script> 