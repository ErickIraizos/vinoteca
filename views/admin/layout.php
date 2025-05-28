<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Panel de Administración' ?> - Vino</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Admin CSS -->
    <link href="<?= BASE_URL ?>public/css/admin.css" rel="stylesheet">
</head>
<body>
    <!-- Incluir el sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Contenido Principal -->
    <main class="main-content">
        <div class="container-fluid py-4">
            <?php if (isset($_SESSION['flash'])): ?>
                <div class="alert alert-<?= $_SESSION['flash']['type'] ?> alert-dismissible fade show" role="alert">
                    <?= $_SESSION['flash']['message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>

            <!-- Encabezado de la página -->
            <div class="page-header mb-4">
                <h1 class="fw-bold"><?= $title ?? 'Panel de Administración' ?></h1>
            </div>

            <?= $content ?? '' ?>
        </div>
    </main>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
    $(document).ready(function() {
        // Cerrar alertas automáticamente
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);

        // Restaurar estado del sidebar
        const sidebarState = localStorage.getItem('sidebarState');
        if (sidebarState === 'show') {
            $('#adminSidebar').addClass('show');
            $('.main-content').addClass('shifted');
            $('.sidebar-overlay').addClass('show');
        } else {
            $('#adminSidebar').removeClass('show');
            $('.main-content').removeClass('shifted');
            $('.sidebar-overlay').removeClass('show');
        }

        // Función para alternar el sidebar y guardar el estado
        window.toggleSidebar = function() {
            $('#adminSidebar').toggleClass('show');
            $('.main-content').toggleClass('shifted');
            $('.sidebar-overlay').toggleClass('show');
            if ($('#adminSidebar').hasClass('show')) {
                localStorage.setItem('sidebarState', 'show');
            } else {
                localStorage.setItem('sidebarState', 'hide');
            }
        }
    });
    </script>

    <style>
    body {
        background-color: #f8f9fa;
    }

    .page-header {
        background: #fff;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    }

    .page-header h1 {
        color: #2c3e50;
        margin: 0;
        font-size: 1.75rem;
    }

    .alert {
        margin-bottom: 2rem;
        border: none;
        border-radius: 0.5rem;
    }

    .container-fluid {
        max-width: 1600px;
    }

    /* Ajustes responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 1rem;
        }

        .page-header h1 {
            font-size: 1.5rem;
        }

        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }
    </style>
</body>
</html> 