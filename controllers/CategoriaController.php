<?php
class CategoriaController extends Controller {
    private $categoriaModel;
    private $productoModel;

    public function __construct() {
        parent::__construct();
        $this->categoriaModel = $this->loadModel('Categoria');
        $this->productoModel = $this->loadModel('Producto');
    }

    public function index() {
        $categorias = $this->categoriaModel->getCategoriasPrincipales();
        
        $this->view('categoria/index', [
            'categorias' => $categorias,
            'title' => 'Categorías',
            'description' => 'Explora nuestras categorías de vinos y licores'
        ]);
    }

    public function ver($id) {
        $categoria = $this->categoriaModel->findById($id);
        if (!$categoria) {
            SessionHelper::setFlash('error', 'La categoría solicitada no existe');
            $this->redirect('');
            return;
        }

        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $porPagina = 12;
        $resultado = $this->productoModel->getByCategoria($categoria['categoria_id'], $pagina, $porPagina);

        $this->view('categoria/ver', [
            'categoria' => $categoria,
            'productos' => $resultado['productos'],
            'total' => $resultado['total'],
            'paginas' => $resultado['paginas'],
            'pagina_actual' => $pagina,
            'title' => $categoria['nombre'] . ' - ' . SITE_NAME,
            'description' => $categoria['descripcion'] ?? 'Explora nuestra selección de ' . $categoria['nombre']
        ]);
    }

    public function subcategoria($slug) {
        $subcategoria = $this->categoriaModel->getBySlug($slug);
        if (!$subcategoria || !$subcategoria['categoria_padre_id']) {
            $this->redirect('categorias');
        }

        $pagina = $_GET['pagina'] ?? 1;
        $filtros = [
            'precio_min' => $_GET['precio_min'] ?? null,
            'precio_max' => $_GET['precio_max'] ?? null,
            'grado_min' => $_GET['grado_min'] ?? null,
            'grado_max' => $_GET['grado_max'] ?? null,
            'pais' => $_GET['pais'] ?? null,
            'ordenar_por' => $_GET['ordenar'] ?? 'fecha_creacion_desc'
        ];

        $productos = $this->productoModel->getByCategoria($subcategoria['categoria_id'], $pagina, $filtros);
        $paises = $this->productoModel->getPaisesByCategoria($subcategoria['categoria_id']);
        $categoriaPadre = $this->categoriaModel->findById($subcategoria['categoria_padre_id']);

        $this->view('categoria/subcategoria', [
            'subcategoria' => $subcategoria,
            'categoria_padre' => $categoriaPadre,
            'productos' => $productos['items'],
            'total' => $productos['total'],
            'paginas' => $productos['paginas'],
            'pagina_actual' => $pagina,
            'paises' => $paises,
            'filtros' => $filtros,
            'title' => $subcategoria['nombre'],
            'description' => $subcategoria['descripcion']
        ]);
    }

    public function filtrar() {
        if (!$this->isAjax()) {
            $this->redirect('categorias');
        }

        $categoriaId = $_GET['categoria_id'];
        $pagina = $_GET['pagina'] ?? 1;
        $filtros = [
            'precio_min' => $_GET['precio_min'] ?? null,
            'precio_max' => $_GET['precio_max'] ?? null,
            'grado_min' => $_GET['grado_min'] ?? null,
            'grado_max' => $_GET['grado_max'] ?? null,
            'pais' => $_GET['pais'] ?? null,
            'ordenar_por' => $_GET['ordenar'] ?? 'fecha_creacion_desc'
        ];

        $productos = $this->productoModel->getByCategoria($categoriaId, $pagina, $filtros);

        $this->jsonResponse([
            'success' => true,
            'productos' => $productos['items'],
            'total' => $productos['total'],
            'paginas' => $productos['paginas']
        ]);
    }

    public function menu() {
        if (!$this->isAjax()) {
            $this->redirect('');
        }

        $categorias = $this->categoriaModel->getMenuCategorias();
        
        $this->jsonResponse([
            'success' => true,
            'categorias' => $categorias
        ]);
    }
} 