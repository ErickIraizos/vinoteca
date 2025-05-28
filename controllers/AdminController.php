<?php
class AdminController extends Controller {
    private $productoModel;
    private $categoriaModel;
    private $pedidoModel;
    private $usuarioModel;
    private $inventarioModel;
    private $reporteModel;
    private $direccionModel;
    private $favoritoModel;

    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->requireAdmin();
        
        $this->productoModel = $this->loadModel('Producto');
        $this->categoriaModel = $this->loadModel('Categoria');
        $this->pedidoModel = $this->loadModel('Pedido');
        $this->usuarioModel = $this->loadModel('Usuario');
        $this->inventarioModel = $this->loadModel('Inventario');
        $this->reporteModel = $this->loadModel('Reporte');
        $this->direccionModel = $this->loadModel('Direccion');
        $this->favoritoModel = $this->loadModel('Favorito');
    }

    public function index() {
        // Obtener estadísticas generales
        $stats = [
            'total_productos' => $this->productoModel->contarTotal(),
            'total_pedidos' => $this->pedidoModel->contarTotal(),
            'total_usuarios' => $this->usuarioModel->contarTotal(),
            'total_categorias' => $this->categoriaModel->contarTotal(),
            'ventas_recientes' => $this->pedidoModel->obtenerVentasRecientes(),
            'productos_populares' => $this->productoModel->getProductosPopulares(5),
            'ultimos_usuarios' => $this->usuarioModel->obtenerUltimos(5),
            'ingresos_totales' => $this->pedidoModel->calcularIngresosTotales()
        ];

        $this->view('admin/dashboard', [
            'title' => 'Panel de Administración',
            'stats' => $stats
        ]);
    }

    public function paneladmin() {
        // Obtener estadísticas generales (igual que index)
        $stats = [
            'total_productos' => $this->productoModel->contarTotal(),
            'total_pedidos' => $this->pedidoModel->contarTotal(),
            'total_usuarios' => $this->usuarioModel->contarTotal(),
            'total_categorias' => $this->categoriaModel->contarTotal(),
            'ventas_recientes' => $this->pedidoModel->obtenerVentasRecientes(),
            'productos_populares' => $this->productoModel->getProductosPopulares(5),
            'ultimos_usuarios' => $this->usuarioModel->obtenerUltimos(5),
            'ingresos_totales' => $this->pedidoModel->calcularIngresosTotales()
        ];
        extract(['stats' => $stats]);
        require 'views/admin/paneladmin.php';
    }

    // Gestión de Productos
    public function productos() {
        $pagina = $_GET['pagina'] ?? 1;
        $busqueda = $_GET['q'] ?? '';
        $productos = $this->productoModel->getAll($pagina, $busqueda);
        $categorias = $this->categoriaModel->getCategoriasPrincipales();
        extract([
            'productos' => $productos['items'],
            'total' => $productos['total'],
            'paginas' => $productos['paginas'],
            'pagina_actual' => $pagina,
            'categorias' => $categorias,
            'busqueda' => $busqueda,
            'title' => 'Gestión de Productos'
        ]);
        require 'views/admin/productos/index.php';
    }

    public function productoForm($id = null) {
        $producto = null;
        if ($id) {
            $producto = $this->productoModel->findById($id);
            if (!$producto) {
                $this->redirect('admin/productos');
            }
        }
        if ($this->isPost()) {
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'precio' => $_POST['precio'],
                'categoria_id' => $_POST['categoria_id'],
                'stock' => $_POST['stock'],
                'grado_alcoholico' => $_POST['grado_alcoholico'],
                'pais_origen' => $_POST['pais_origen'],
                'marca' => $_POST['marca'],
                'destacado' => isset($_POST['destacado']) ? 1 : 0,
                'activo' => isset($_POST['activo']) ? 1 : 0,
                'imagen_url' => $_POST['imagen_url'] ?? null
            ];
            if (isset($_POST['galeria_urls']) && is_array($_POST['galeria_urls'])) {
                $galeria = array_filter(array_map('trim', $_POST['galeria_urls']));
                if (!empty($galeria)) {
                    $data['galeria'] = json_encode($galeria);
                }
            }
            if ($id) {
                $this->productoModel->update($id, $data);
                SessionHelper::setFlash('success', 'Producto actualizado correctamente');
            } else {
                $this->productoModel->create($data);
                SessionHelper::setFlash('success', 'Producto creado correctamente');
            }
            $this->redirect('admin/productos');
        }
        $categorias = $this->categoriaModel->getCategoriasPrincipales();
        extract([
            'producto' => $producto,
            'categorias' => $categorias,
            'title' => $producto ? 'Editar Producto' : 'Nuevo Producto'
        ]);
        require 'views/admin/productos/form.php';
    }

    public function eliminarProducto($id) {
        try {
            if (!$id) {
                SessionHelper::setFlash('error', 'ID de producto no válido');
                $this->redirect('admin/productos');
            }

            $producto = $this->productoModel->findById($id);
            if (!$producto) {
                SessionHelper::setFlash('error', 'Producto no encontrado');
                $this->redirect('admin/productos');
            }

            // Si el producto tiene una imagen, eliminarla
            if (!empty($producto['imagen_url'])) {
                $rutaImagen = UPLOAD_PATH . basename($producto['imagen_url']);
                if (file_exists($rutaImagen)) {
                    unlink($rutaImagen);
                }
            }

            // Eliminar el producto
            if ($this->productoModel->delete($id)) {
                SessionHelper::setFlash('success', 'Producto eliminado correctamente');
            } else {
                SessionHelper::setFlash('error', 'Error al eliminar el producto');
            }
        } catch (Exception $e) {
            error_log("Error al eliminar producto: " . $e->getMessage());
            SessionHelper::setFlash('error', 'Error al eliminar el producto');
        }

        $this->redirect('admin/productos');
    }

    // Gestión de Categorías
    public function categorias() {
        $categorias = $this->categoriaModel->getAll();
        extract([
            'categorias' => $categorias,
            'title' => 'Gestión de Categorías'
        ]);
        require 'views/admin/categorias/index.php';
    }

    public function categoriaForm($id = null) {
        $categoria = null;
        if ($id) {
            $categoria = $this->categoriaModel->findById($id);
            if (!$categoria) {
                $this->redirect('admin/categorias');
            }
        }
        if ($this->isPost()) {
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'activa' => isset($_POST['activa']) ? 1 : 0,
                'imagen_url' => $_POST['imagen_url'] ?? null
            ];
            if ($id) {
                $this->categoriaModel->update($id, $data);
                SessionHelper::setFlash('success', 'Categoría actualizada correctamente');
            } else {
                $this->categoriaModel->create($data);
                SessionHelper::setFlash('success', 'Categoría creada correctamente');
            }
            $this->redirect('admin/categorias');
        }
        extract([
            'categoria' => $categoria,
            'title' => $categoria ? 'Editar Categoría' : 'Nueva Categoría'
        ]);
        require 'views/admin/categorias/form.php';
    }

    public function eliminarCategoria($id) {
        try {
            if (!$id) {
                SessionHelper::setFlash('error', 'ID de categoría no válido');
                $this->redirect('admin/categorias');
            }

            $categoria = $this->categoriaModel->findById($id);
            if (!$categoria) {
                SessionHelper::setFlash('error', 'Categoría no encontrada');
                $this->redirect('admin/categorias');
            }

            $this->categoriaModel->delete($id);
            SessionHelper::setFlash('success', 'Categoría eliminada correctamente');
        } catch (Exception $e) {
            error_log("Error al eliminar categoría: " . $e->getMessage());
            SessionHelper::setFlash('error', 'Error al eliminar la categoría');
        }

        $this->redirect('admin/categorias');
    }

    // Gestión de Pedidos
    public function pedidos() {
        $estado = $_GET['estado'] ?? '';
        $pagina = $_GET['pagina'] ?? 1;
        $pedidos = $this->pedidoModel->getAll($pagina, $estado);
        extract([
            'pedidos' => $pedidos['items'],
            'total' => $pedidos['total'],
            'paginas' => $pedidos['paginas'],
            'pagina_actual' => $pagina,
            'estado_actual' => $estado,
            'title' => 'Gestión de Pedidos'
        ]);
        require 'views/admin/pedidos/index.php';
    }

    public function verPedido($id = null) {
        if (!$id) {
            SessionHelper::setFlash('error', 'ID de pedido no válido');
            $this->redirect('admin/pedidos');
        }
        $pedido = $this->pedidoModel->getDetalle($id);
        if (!$pedido) {
            SessionHelper::setFlash('error', 'Pedido no encontrado');
            $this->redirect('admin/pedidos');
        }
        extract([
            'pedido' => $pedido,
            'title' => 'Detalle del Pedido #' . $id
        ]);
        require 'views/admin/pedidos/detalle.php';
    }

    public function editarPedido($id = null) {
        if (!$id) {
            SessionHelper::setFlash('error', 'ID de pedido no válido');
            $this->redirect('admin/pedidos');
        }
        $pedido = $this->pedidoModel->getDetalle($id);
        if (!$pedido) {
            SessionHelper::setFlash('error', 'Pedido no encontrado');
            $this->redirect('admin/pedidos');
        }
        if (isset($_GET['estado'])) {
            $estado = $_GET['estado'];
            if ($this->pedidoModel->actualizarEstado($id, $estado)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
            exit;
        }
        extract([
            'pedido' => $pedido,
            'title' => 'Editar Pedido #' . $id
        ]);
        require 'views/admin/pedidos/editar.php';
    }

    // Gestión de Usuarios
    public function usuarios() {
        $pagina = $_GET['pagina'] ?? 1;
        $busqueda = $_GET['q'] ?? '';
        $usuarios = $this->usuarioModel->getAll($pagina, $busqueda);
        extract([
            'usuarios' => $usuarios['items'],
            'total' => $usuarios['total'],
            'paginas' => $usuarios['paginas'],
            'pagina_actual' => $pagina,
            'busqueda' => $busqueda,
            'title' => 'Gestión de Usuarios'
        ]);
        require 'views/admin/usuarios/index.php';
    }

    public function usuariosDetalle($id) {
        $usuario = $this->usuarioModel->getDetalle($id);
        if (!$usuario) {
            SessionHelper::setFlash('error', 'Usuario no encontrado');
            $this->redirect('admin/usuarios');
        }
        // Mostrar la vista de detalle de usuario SIN layout global
        require 'views/admin/usuarios/detalle.php';
    }

    public function usuariosEditar($id) {
        $usuario = $this->usuarioModel->findById($id);
        if (!$usuario) {
            SessionHelper::setFlash('error', 'Usuario no encontrado');
            $this->redirect('admin/usuarios');
        }
        if ($this->isPost()) {
            $data = [
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'email' => $_POST['email'],
                'rol' => $_POST['rol'],
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];
            $errores = [];
            if (empty($data['nombre'])) {
                $errores['nombre'] = 'El nombre es requerido';
            }
            if (empty($data['apellido'])) {
                $errores['apellido'] = 'El apellido es requerido';
            }
            if (empty($data['email'])) {
                $errores['email'] = 'El email es requerido';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errores['email'] = 'El email no es válido';
            }
            if ($this->usuarioModel->emailExiste($data['email']) && $data['email'] !== $usuario['email']) {
                $errores['email'] = 'Este email ya está registrado';
            }
            if (empty($errores)) {
                if ($this->usuarioModel->update($id, $data)) {
                    SessionHelper::setFlash('success', 'Usuario actualizado correctamente');
                    $this->redirect('admin/usuarios');
                } else {
                    SessionHelper::setFlash('error', 'Error al actualizar el usuario');
                }
            } else {
                SessionHelper::set('errores', $errores);
                SessionHelper::set('old', $data);
            }
        }
        extract([
            'usuario' => $usuario,
            'title' => 'Editar Usuario',
            'errores' => SessionHelper::get('errores', []),
            'old' => SessionHelper::get('old', [])
        ]);
        require 'views/admin/usuarios/form.php';
        SessionHelper::delete('errores');
        SessionHelper::delete('old');
    }

    public function usuariosEliminar($id) {
        try {
            if (!$id) {
                error_log("Error: ID de usuario no proporcionado");
                SessionHelper::setFlash('error', 'ID de usuario no válido');
                $this->redirect('admin/usuarios');
                return;
            }

            $usuario = $this->usuarioModel->findById($id);
            if (!$usuario) {
                error_log("Error: Usuario no encontrado con ID: " . $id);
                SessionHelper::setFlash('error', 'Usuario no encontrado');
                $this->redirect('admin/usuarios');
                return;
            }

            // No permitir eliminar usuarios admin
            if ($usuario['rol'] === 'admin') {
                error_log("Error: Intento de eliminar usuario admin con ID: " . $id);
                SessionHelper::setFlash('error', 'No se puede eliminar un usuario administrador');
                $this->redirect('admin/usuarios');
                return;
            }

            error_log("Intentando eliminar usuario con ID: " . $id);
            if ($this->usuarioModel->delete($id)) {
                error_log("Usuario eliminado exitosamente con ID: " . $id);
                SessionHelper::setFlash('success', 'Usuario eliminado correctamente');
            } else {
                error_log("Error al eliminar usuario con ID: " . $id);
                SessionHelper::setFlash('error', 'Error al eliminar el usuario');
            }
        } catch (Exception $e) {
            error_log("Excepción al eliminar usuario: " . $e->getMessage());
            SessionHelper::setFlash('error', 'Error al eliminar el usuario');
        }

        $this->redirect('admin/usuarios');
    }

    public function usuariosCrear() {
        if ($this->isPost()) {
            $data = [
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'email' => $_POST['email'],
                'rol' => $_POST['rol'],
                'activo' => isset($_POST['activo']) ? 1 : 0,
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
            ];
            $errores = [];
            if (empty($data['nombre'])) {
                $errores['nombre'] = 'El nombre es requerido';
            }
            if (empty($data['apellido'])) {
                $errores['apellido'] = 'El apellido es requerido';
            }
            if (empty($data['email'])) {
                $errores['email'] = 'El email es requerido';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errores['email'] = 'El email no es válido';
            }
            if (empty($_POST['password'])) {
                $errores['password'] = 'La contraseña es requerida';
            }
            if ($this->usuarioModel->emailExiste($data['email'])) {
                $errores['email'] = 'Este email ya está registrado';
            }
            if (empty($errores)) {
                if ($this->usuarioModel->crear($data)) {
                    SessionHelper::setFlash('success', 'Usuario creado correctamente');
                    $this->redirect('admin/usuarios');
                } else {
                    SessionHelper::setFlash('error', 'Error al crear el usuario');
                }
            } else {
                SessionHelper::set('errores', $errores);
                SessionHelper::set('old', $data);
            }
        }
        extract([
            'usuario' => [],
            'title' => 'Nuevo Usuario',
            'errores' => SessionHelper::get('errores', []),
            'old' => SessionHelper::get('old', [])
        ]);
        require 'views/admin/usuarios/form.php';
        SessionHelper::delete('errores');
        SessionHelper::delete('old');
    }

    // Reportes y Estadísticas
    public function reportes() {
        $tipo = $_GET['tipo'] ?? 'ventas';
        $desde = $_GET['desde'] ?? date('Y-01-01');
        $hasta = $_GET['hasta'] ?? date('Y-m-d');
        $datos = $this->reporteModel->getDatos($tipo, $desde, $hasta);
        $ultimas_ventas = $this->pedidoModel->obtenerVentasRecientes(10);
        foreach ($ultimas_ventas as &$venta) {
            $venta['cliente_nombre'] = $venta['nombre_cliente'] ?? 'Cliente no registrado';
            $venta['total_productos'] = $this->pedidoModel->getTotalProductosPedido($venta['pedido_id']);
            $venta['estado_color'] = $this->getEstadoColor($venta['estado']);
            $venta['fecha'] = $venta['fecha_pedido'];
        }
        $stats = [
            'ventas_totales' => $this->reporteModel->getVentasTotales($desde, $hasta),
            'pedidos_completados' => $this->reporteModel->getPedidosCompletados($desde, $hasta),
            'productos_vendidos' => $this->reporteModel->getProductosVendidos($desde, $hasta),
            'nuevos_clientes' => $this->reporteModel->getNuevosClientes($desde, $hasta),
            'ventas_por_mes' => $this->reporteModel->getVentasPorMes($desde, $hasta),
            'meses' => $this->reporteModel->getMeses($desde, $hasta),
            'productos_populares' => $this->reporteModel->getProductosPopulares($desde, $hasta),
            'ventas_por_categoria' => $this->reporteModel->getVentasPorCategoria($desde, $hasta)
        ];
        extract([
            'datos' => $datos,
            'stats' => $stats,
            'tipo' => $tipo,
            'desde' => $desde,
            'hasta' => $hasta,
            'ultimas_ventas' => $ultimas_ventas,
            'title' => 'Reportes y Estadísticas'
        ]);
        require 'views/admin/reportes/index.php';
    }

    private function getEstadoColor($estado) {
        switch (strtolower($estado)) {
            case 'pendiente':
                return 'warning';
            case 'en proceso':
                return 'info';
            case 'completado':
            case 'entregado':
                return 'success';
            case 'cancelado':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    public function perfil() {
        $usuario_id = $_SESSION['usuario_id'];
        $usuario = $this->usuarioModel->getDetalle($usuario_id);
        if ($this->isPost()) {
            $data = [
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'email' => $_POST['email'],
                'telefono' => $_POST['telefono'] ?? null,
                'direccion' => $_POST['direccion'] ?? null
            ];
            if ($this->usuarioModel->emailExiste($_POST['email']) && $_POST['email'] !== $usuario['email']) {
                SessionHelper::setFlash('error', 'El email ya está registrado');
                SessionHelper::set('old', $data);
            } else {
                if (!empty($_POST['password'])) {
                    $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                }
                if ($this->usuarioModel->update($usuario_id, $data)) {
                    SessionHelper::setFlash('success', 'Perfil actualizado correctamente');
                    $this->redirect('admin/perfil');
                } else {
                    SessionHelper::setFlash('error', 'Error al actualizar el perfil');
                    SessionHelper::set('old', $data);
                }
            }
        }
        extract([
            'usuario' => $usuario,
            'title' => 'Mi Perfil',
            'old' => SessionHelper::get('old', [])
        ]);
        require 'views/admin/perfil.php';
        SessionHelper::delete('old');
    }

    // Métodos auxiliares
    private function subirImagen($imagen) {
        $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION);
        $nombreArchivo = uniqid() . '.' . $extension;
        $rutaDestino = UPLOAD_PATH . $nombreArchivo;

        if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
            return 'uploads/' . $nombreArchivo;
        }

        return null;
    }

    protected function requireAdmin() {
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
            SessionHelper::setFlash('error', 'Acceso denegado. Se requieren privilegios de administrador.');
            $this->redirect('');
            exit();
        }
    }

    public function exportarReporte() {
        // Limpiar cualquier salida anterior
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        $tipo = $_GET['tipo'] ?? 'ventas';
        $desde = $_GET['desde'] ?? date('Y-m-d', strtotime('-30 days'));
        $hasta = $_GET['hasta'] ?? date('Y-m-d');
        $formato = $_GET['formato'] ?? 'csv';

        $datos = $this->reporteModel->getDatos($tipo, $desde, $hasta);
        
        if ($formato === 'pdf') {
            require_once 'classes/ReportePDF.php';
            
            // Crear instancia de PDF
            $titulo = 'Reporte de ' . ucfirst($tipo);
            $periodo = 'Período: ' . date('d/m/Y', strtotime($desde)) . ' - ' . date('d/m/Y', strtotime($hasta));
            $pdf = new ReportePDF($titulo, $periodo);
            
            // Agregar página
            $pdf->AddPage();
            
            // Obtener estadísticas
            $stats = [
                'ventas_totales' => $this->reporteModel->getVentasTotales($desde, $hasta),
                'pedidos_completados' => $this->reporteModel->getPedidosCompletados($desde, $hasta),
                'productos_vendidos' => $this->reporteModel->getProductosVendidos($desde, $hasta),
                'nuevos_clientes' => $this->reporteModel->getNuevosClientes($desde, $hasta)
            ];
            
            // Agregar estadísticas generales
            $pdf->addEstadisticas($stats);
            
            // Agregar tabla según el tipo de reporte
            switch ($tipo) {
                case 'ventas':
                    $pdf->addTablaVentas($datos);
                    break;
                case 'productos':
                    $pdf->addTablaProductos($datos);
                    break;
                case 'usuarios':
                    $pdf->addTablaUsuarios($datos);
                    break;
            }
            
            // Generar el PDF
            $pdf->Output('reporte_' . $tipo . '_' . date('Y-m-d') . '.pdf', 'D');
            exit();
        } else {
            // Exportar como CSV
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=reporte_' . $tipo . '_' . date('Y-m-d') . '.csv');
            
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            switch ($tipo) {
                case 'ventas':
                    fputcsv($output, ['Fecha', 'Total de Pedidos', 'Total Ventas (€)']);
                    foreach ($datos as $fila) {
                        fputcsv($output, [
                            $fila['fecha'],
                            $fila['total_pedidos'],
                            number_format($fila['total_ventas'], 2, ',', '.')
                        ]);
                    }
                    break;
                    
                case 'productos':
                    fputcsv($output, ['Producto', 'Cantidad Vendida', 'Total Ventas (€)']);
                    foreach ($datos as $fila) {
                        fputcsv($output, [
                            $fila['nombre'],
                            $fila['cantidad_total'],
                            number_format($fila['total_ventas'], 2, ',', '.')
                        ]);
                    }
                    break;
                    
                case 'usuarios':
                    fputcsv($output, ['Nombre', 'Email', 'Total Pedidos', 'Total Compras (€)']);
                    foreach ($datos as $fila) {
                        fputcsv($output, [
                            $fila['nombre'],
                            $fila['email'],
                            $fila['total_pedidos'],
                            number_format($fila['total_compras'], 2, ',', '.')
                        ]);
                    }
                    break;
            }
            
            fclose($output);
            exit();
        }
    }

    public function imprimirReporte() {
        $tipo = $_GET['tipo'] ?? 'ventas';
        $desde = $_GET['desde'] ?? date('Y-m-d', strtotime('-30 days'));
        $hasta = $_GET['hasta'] ?? date('Y-m-d');

        $datos = $this->reporteModel->getDatos($tipo, $desde, $hasta);
        $stats = [
            'ventas_totales' => $this->reporteModel->getVentasTotales($desde, $hasta),
            'pedidos_completados' => $this->reporteModel->getPedidosCompletados($desde, $hasta),
            'productos_vendidos' => $this->reporteModel->getProductosVendidos($desde, $hasta),
            'nuevos_clientes' => $this->reporteModel->getNuevosClientes($desde, $hasta)
        ];

        // Cargar vista especial para imprimir
        $this->view('admin/reportes/imprimir', [
            'datos' => $datos,
            'stats' => $stats,
            'tipo' => $tipo,
            'desde' => $desde,
            'hasta' => $hasta,
            'title' => 'Imprimir Reporte'
        ]);
    }

    // Gestión de Promociones
    public function promociones() {
        $promocionModel = $this->loadModel('Promocion');
        $promociones = $promocionModel->getAll();
        require 'views/admin/promociones/index.php';
    }

    public function promocionesCrear() {
        $promocionModel = $this->loadModel('Promocion');
        if ($this->isPost()) {
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'descuento_porcentaje' => $_POST['descuento_porcentaje'],
                'fecha_inicio' => $_POST['fecha_inicio'],
                'fecha_fin' => $_POST['fecha_fin'],
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];
            $id = $promocionModel->create($data);
            $this->redirect('admin/promociones');
        }
        require 'views/admin/promociones/crear.php';
    }

    public function promocionesEditar($id) {
        $promocionModel = $this->loadModel('Promocion');
        $promocion = $promocionModel->findById($id);
        if ($this->isPost()) {
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'descuento_porcentaje' => $_POST['descuento_porcentaje'],
                'fecha_inicio' => $_POST['fecha_inicio'],
                'fecha_fin' => $_POST['fecha_fin'],
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];
            $promocionModel->update($id, $data);
            $this->redirect('admin/promociones');
        }
        require 'views/admin/promociones/editar.php';
    }

    public function promocionesEliminar($id) {
        $promocionModel = $this->loadModel('Promocion');
        $promocionModel->delete($id);
        $this->redirect('admin/promociones');
    }

    public function promocionesProductos($id) {
        $promocionModel = $this->loadModel('Promocion');
        $productoModel = $this->loadModel('Producto');
        $promocion = $promocionModel->findById($id);
        $productosAsociados = $promocionModel->getProductos($id);
        // Obtener IDs de productos ya asociados
        $idsAsociados = array_column($productosAsociados, 'producto_id');
        // Obtener todos los productos activos
        $todos = $productoModel->getAll(1, '', 1000)['items'];
        // Filtrar los que no están asociados
        $productosDisponibles = array_filter($todos, function($p) use ($idsAsociados) {
            return !in_array($p['producto_id'], $idsAsociados);
        });
        require 'views/admin/promociones/productos.php';
    }

    public function promocionesAsociarProducto($id) {
        $promocionModel = $this->loadModel('Promocion');
        if ($this->isPost()) {
            $producto_id = $_POST['producto_id'];
            $promocionModel->asociarProducto($id, $producto_id);
        }
        $this->redirect('admin/promociones/productos/' . $id);
    }

    public function promocionesDesasociarProducto($id, $producto_id) {
        $promocionModel = $this->loadModel('Promocion');
        $promocionModel->desasociarProducto($id, $producto_id);
        $this->redirect('admin/promociones/productos/' . $id);
    }
} 