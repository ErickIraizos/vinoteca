<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <meta name="description" content="<?php echo isset($description) ? $description : 'Tu tienda online de vinos y licores'; ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>public/img/favicon.png">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    
    <!-- Fuentes personalizadas -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- Scripts necesarios -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Estilos personalizados -->
    <style>
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            background: none !important;
            box-shadow: none !important;
        }
        body > *:first-child {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        .badge {
            font-size: 0.9em;
            padding: 0.5em 1em;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        /* Estilo personalizado para SweetAlert2 */
        .swal2-popup {
            font-size: 1rem;
        }
        .swal2-title {
            font-size: 1.5rem;
        }
        .swal2-content {
            font-size: 1rem;
        }
        .swal2-confirm {
            background-color: #8B4513 !important;
        }
        .swal2-cancel {
            background-color: #6c757d !important;
        }
        /* Estilos para el menú de usuario */
        .user-dropdown {
            cursor: pointer;
        }
        .user-dropdown .dropdown-menu {
            margin-top: 0.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .user-dropdown .dropdown-item {
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
        }
        .user-dropdown .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        .user-dropdown .dropdown-item i {
            width: 1.5rem;
            text-align: center;
            margin-right: 0.5rem;
        }
        .dropdown-menu {
            margin-top: 0.5rem;
        }
        .user-menu {
            position: relative;
            display: inline-block;
        }
        
        .user-menu button {
            background-color: #fff;
            border: 1px solid #8B4513;
            color: #8B4513;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .user-menu button:hover {
            background-color: #8B4513;
            color: #fff;
        }
        
        .user-menu-content {
            display: none;
            position: absolute;
            right: 0;
            top: 120%;
            background-color: #fff;
            min-width: 220px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            z-index: 1000;
            border-radius: 8px;
            border: none;
            padding: 0.5rem 0;
            animation: fadeIn 0.2s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .user-menu-content::before {
            content: '';
            position: absolute;
            top: -8px;
            right: 20px;
            width: 16px;
            height: 16px;
            background-color: #fff;
            transform: rotate(45deg);
            border-left: 1px solid #f0f0f0;
            border-top: 1px solid #f0f0f0;
        }
        
        .user-menu-content a {
            color: #333;
            padding: 12px 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        
        .user-menu-content a:hover {
            background-color: #f8f9fa;
            color: #8B4513;
            padding-left: 24px;
        }
        
        .user-menu-content a i {
            width: 20px;
            text-align: center;
            margin-right: 12px;
            font-size: 1.1em;
        }
        
        .user-menu-content .dropdown-divider {
            height: 1px;
            margin: 0.5rem 0;
            background-color: #f0f0f0;
            border: none;
        }
        
        .user-menu-content a.text-danger {
            color: #dc3545;
        }
        
        .user-menu-content a.text-danger:hover {
            background-color: #fff5f5;
            color: #dc3545;
        }
        
        .show {
            display: block;
        }
        
        /* Estilo para el botón del carrito */
        .cart-button {
            background-color: #fff;
            border: 1px solid #8B4513;
            color: #8B4513;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
        }
        
        .cart-button:hover {
            background-color: #8B4513;
            color: #fff;
        }
        
        .cart-button .badge {
            background-color: #dc3545;
            margin-left: 8px;
            font-size: 0.8em;
            padding: 0.25em 0.6em;
            border-radius: 50%;
        }

        /* Estilos para el botón del sidebar */
        .sidebar-toggle {
            background: none;
            border: none;
            color: #fff;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            margin-right: 0.5rem;
        }

        .sidebar-toggle:hover {
            color: rgba(255,255,255,0.8);
        }

        .sidebar-toggle i {
            transition: transform 0.3s ease;
        }

        .sidebar-collapsed .sidebar-toggle i {
            transform: rotate(180deg);
        }

        @media (max-width: 768px) {
            .sidebar-toggle {
                padding: 0.5rem;
                margin-right: 0;
                order: 1;
            }
            .navbar-toggler {
                order: 2;
            }
            .navbar-collapse {
                order: 3;
                width: 100%;
            }
        }

        /* Ajuste para el navbar con el botón del sidebar */
        .navbar .container {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .navbar-nav {
            margin-left: 0;
        }
    </style>
</head>
<body style="margin-top:-10; padding-top:-10;">
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
    <?php /* Eliminado el botón sidebar-toggle para admin */ ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?php echo $_SESSION['error']; ?>',
            confirmButtonColor: '#8B4513'
        });
    </script>
    <?php unset($_SESSION['error']); endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '<?php echo $_SESSION['success']; ?>',
            confirmButtonColor: '#8B4513'
        });
    </script>
    <?php unset($_SESSION['success']); endif; ?>

    <!-- Header -->
    <header class="header">
        <!-- Main Header -->
        <div class="main-header py-3">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Logo -->
                    <div class="col-md-3">
                        <a href="<?php echo BASE_URL; ?>" class="logo d-flex align-items-center">
                            <i class="fas fa-wine-bottle fa-2x me-2 text-primary"></i>
                            <span class="fw-bold" style="font-size:1.5rem; color:#8B4513;">Vinoteca Online</span>
                        </a>
                    </div>
                    
                    <!-- Buscador -->
                    <div class="col-md-6">
                        <form action="<?php echo BASE_URL; ?>productos" method="GET" class="search-form">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Buscar productos..." name="buscar" id="search-input">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Iconos de usuario -->
                    <div class="col-md-3">
                        <div class="user-actions text-end">
                            <?php if (isset($_SESSION['usuario_id'])): ?>
                                <div class="user-menu">
                                    <button onclick="toggleUserMenu()" class="btn">
                                        <i class="fas fa-user"></i>
                                        <span class="ms-2"><?php echo $_SESSION['usuario_nombre']; ?></span>
                                        <i class="fas fa-chevron-down ms-2"></i>
                                    </button>
                                    <div id="userMenuContent" class="user-menu-content">
                                        <a href="<?php echo BASE_URL; ?>perfil">
                                            <i class="fas fa-user-circle"></i>
                                            Mi Perfil
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>pedidos">
                                            <i class="fas fa-shopping-bag"></i>
                                            Mis Pedidos
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>favoritos">
                                            <i class="fas fa-heart"></i>
                                            Favoritos
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a href="<?php echo BASE_URL; ?>auth/logout" class="text-danger">
                                            <i class="fas fa-sign-out-alt"></i>
                                            Cerrar Sesión
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <a href="<?php echo BASE_URL; ?>auth/login" class="btn">
                                    <i class="fas fa-user"></i>
                                    <span class="ms-2">Iniciar Sesión</span>
                                </a>
                            <?php endif; ?>
                            
                            <a href="<?php echo BASE_URL; ?>carrito" class="cart-button ms-2">
                                <i class="fas fa-shopping-cart"></i>
                                <?php if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>
                                    <span class="badge">
                                        <?php echo count($_SESSION['carrito']); ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menú de navegación -->
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <?php /* Eliminado el botón sidebar-toggle para admin */ ?>
                <?php endif; ?>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav">
                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>paneladmin">Panel Admin</a>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>productos">Productos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>categoria">Categorías</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>productos/ofertas">Ofertas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>productos/novedades">Novedades</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>blog">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>contacto">Contacto</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Contenido principal -->
    <main class="main-content">

    <!-- Script para inicializar los dropdowns -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar todos los dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        });
    </script>

    <script>
    // Función para mostrar/ocultar el menú de usuario
    function toggleUserMenu() {
        document.getElementById("userMenuContent").classList.toggle("show");
    }

    // Cerrar el menú si el usuario hace clic fuera de él
    window.onclick = function(event) {
        if (!event.target.matches('.btn')) {
            var dropdowns = document.getElementsByClassName("user-menu-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
    </script> 