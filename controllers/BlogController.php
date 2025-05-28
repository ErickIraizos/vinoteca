<?php
class BlogController extends Controller {
    private $productoModel;
    private $categoriaModel;

    public function __construct() {
        parent::__construct();
        $this->productoModel = $this->loadModel('Producto');
        $this->categoriaModel = $this->loadModel('Categoria');
    }

    public function index() {
        // Obtener página actual
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $porPagina = 6; // Productos por página
        
        // Obtener productos destacados como "artículos"
        $productos = $this->productoModel->getDestacados($pagina, $porPagina);
        
        // Obtener categorías para el sidebar
        $categorias = $this->categoriaModel->getCategoriasPrincipales();
        
        $this->view('blog/index', [
            'articulos' => $productos['productos'],
            'total' => $productos['total'],
            'paginas' => $productos['paginas'],
            'pagina_actual' => $pagina,
            'categorias' => $categorias,
            'title' => 'Blog - Mundo del Vino',
            'description' => 'Descubre artículos sobre vinos, maridajes y cultura vinícola'
        ]);
    }

    public function categoria($id) {
        $categoria = $this->categoriaModel->findById($id);
        if (!$categoria) {
            $this->redirect('blog');
        }
        
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $porPagina = 6;
        
        $productos = $this->productoModel->getByCategoria($categoria['categoria_id'], $pagina, $porPagina);
        
        // Obtener todas las categorías para el sidebar
        $categorias = $this->categoriaModel->getCategoriasPrincipales();
        
        $this->view('blog/categoria', [
            'categoria' => $categoria,
            'articulos' => $productos['productos'],
            'total' => $productos['total'],
            'paginas' => $productos['paginas'],
            'pagina_actual' => $pagina,
            'categorias' => $categorias,
            'title' => 'Blog - ' . $categoria['nombre'],
            'description' => 'Artículos sobre ' . $categoria['nombre']
        ]);
    }
} 