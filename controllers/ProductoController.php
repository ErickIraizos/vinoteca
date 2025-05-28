<?php
class ProductoController extends Controller {
    private $productoModel;
    private $categoriaModel;
    private $resenaModel;
    private $inventarioModel;

    public function __construct() {
        parent::__construct();
        $this->productoModel = $this->loadModel('Producto');
        $this->categoriaModel = $this->loadModel('Categoria');
        $this->resenaModel = $this->loadModel('Resena');
        $this->inventarioModel = $this->loadModel('Inventario');
    }

    public function index() {
        // Obtener parámetros de filtrado y paginación
        $categoria_id = $_GET['categoria'] ?? null;
        $busqueda = $_GET['buscar'] ?? null;
        $orden = $_GET['orden'] ?? 'nombre';
        $pagina = $_GET['pagina'] ?? 1;
        $por_pagina = 12;

        // Obtener productos con filtros
        $productos = $this->productoModel->getProductos(
            $categoria_id,
            $busqueda,
            $orden,
            $pagina,
            $por_pagina
        );

        // Obtener categorías para el filtro
        $categorias = $this->categoriaModel->getCategorias();

        $this->view('productos/index', [
            'title' => 'Nuestros Vinos',
            'description' => 'Explora nuestra selección de vinos',
            'productos' => $productos,
            'pagina_actual' => $pagina,
            'categorias' => $categorias,
            'categoria_actual' => $categoria_id,
            'busqueda' => $busqueda,
            'orden' => $orden
        ]);
    }

    public function ofertas() {
        // Obtener página actual
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $porPagina = ITEMS_PER_PAGE;
        $categoria_id = isset($_GET['categoria']) ? (int)$_GET['categoria'] : null;
        
        // Obtener ofertas con paginación y filtro de categoría
        $ofertas = $this->productoModel->getOfertas($porPagina, $pagina, $categoria_id);
        
        // Obtener categorías para el filtro lateral
        $categorias = $this->categoriaModel->getCategoriasPrincipales();
        
        $this->view('productos/ofertas', [
            'ofertas' => $ofertas,
            'categorias' => $categorias,
            'pagina_actual' => $pagina,
            'categoria_actual' => $categoria_id,
            'title' => 'Ofertas Especiales',
            'description' => 'Las mejores ofertas en vinos y licores'
        ]);
    }

    public function novedades() {
        // Obtener página actual
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $categoria_id = isset($_GET['categoria']) ? (int)$_GET['categoria'] : null;
        $porPagina = defined('ITEMS_PER_PAGE') ? ITEMS_PER_PAGE : 12;

        // Obtener novedades con o sin filtro de categoría
        if ($categoria_id) {
            $resultado = $this->productoModel->getByCategoria($categoria_id, $pagina, $porPagina);
            $novedades = $resultado['productos'];
            $total = $resultado['total'];
            $total_paginas = $resultado['paginas'];
            $pagina_actual = $pagina;
        } else {
            $resultado = $this->productoModel->getNovedades($pagina, $porPagina);
            $novedades = $resultado['productos'];
            $total = $resultado['total'];
            $total_paginas = $resultado['paginas'];
            $pagina_actual = $resultado['pagina_actual'];
        }

        // Obtener categorías para el filtro lateral
        $categorias = $this->categoriaModel->getCategoriasPrincipales();

        $this->view('productos/novedades', [
            'novedades' => $novedades,
            'total' => $total,
            'total_paginas' => $total_paginas,
            'pagina_actual' => $pagina_actual,
            'categorias' => $categorias,
            'title' => 'Nuevos Productos',
            'description' => 'Descubre nuestras últimas incorporaciones en vinos y licores'
        ]);
    }

    public function detalle($id) {
        $producto = $this->productoModel->getDetalle($id);
        
        if (!$producto) {
            echo "Error: Producto no encontrado - ID: " . $id;
            echo "<pre>";
            debug_print_backtrace();
            echo "</pre>";
            exit();
        }

        // Verificar si el producto es favorito del usuario actual
        if (isset($_SESSION['usuario_id'])) {
            $producto['es_favorito'] = $this->productoModel->esFavorito($_SESSION['usuario_id'], $id);
        }

        // Obtener productos relacionados
        $relacionados = $this->productoModel->getRelacionados(
            $producto['categoria_id'],
            $producto['producto_id'],
            4
        );

        $this->view('productos/detalle', [
            'title' => $producto['nombre'],
            'description' => $producto['descripcion'],
            'producto' => $producto,
            'relacionados' => $relacionados
        ]);
    }

    public function categoria($id) {
        $categoria = $this->categoriaModel->findById($id);
        
        if (!$categoria) {
            echo "Error: Categoría no encontrada - ID: " . $id;
            echo "<pre>";
            debug_print_backtrace();
            echo "</pre>";
            exit();
        }

        $_GET['categoria'] = $id;
        $this->index();
    }

    public function agregarResena() {
        if (!$this->isPost() || !$this->isAuthenticated()) {
            $this->redirect('auth/login');
        }

        $data = [
            'producto_id' => $_POST['producto_id'],
            'usuario_id' => $_SESSION['usuario_id'],
            'calificacion' => $_POST['calificacion'],
            'comentario' => $_POST['comentario']
        ];

        if ($this->resenaModel->crearResena($data)) {
            SessionHelper::setFlash('success', '¡Gracias por tu reseña!');
        } else {
            SessionHelper::setFlash('error', 'No se pudo publicar tu reseña');
        }

        $this->redirect('producto/' . $data['producto_id']);
    }

    public function buscar() {
        if ($this->isPost()) {
            $busqueda = $_POST['buscar'] ?? '';
            $this->redirect('productos?buscar=' . urlencode($busqueda));
        }
        
        $this->redirect('productos');
    }

    public function toggleFavorito() {
        if (!$this->isAuthenticated()) {
            $this->jsonResponse(['success' => false, 'message' => 'Debes iniciar sesión']);
        }

        $productoId = $_POST['producto_id'];
        $resultado = $this->productoModel->toggleFavorito($_SESSION['usuario_id'], $productoId);
        
        $this->jsonResponse([
            'success' => true,
            'isFavorito' => $resultado,
            'message' => $resultado ? 'Añadido a favoritos' : 'Eliminado de favoritos'
        ]);
    }

    public function verificarStock() {
        if ($this->isAjax()) {
            $productoId = $_POST['producto_id'];
            $cantidad = $_POST['cantidad'];
            
            $stock = $this->inventarioModel->getStock($productoId);
            $disponible = $stock >= $cantidad;
            
            $this->jsonResponse([
                'success' => true,
                'disponible' => $disponible,
                'stock' => $stock
            ]);
        }
    }
} 