<?php
class FavoritosController extends Controller {
    private $favoritoModel;
    private $productoModel;

    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        
        $this->favoritoModel = $this->loadModel('Favorito');
        $this->productoModel = $this->loadModel('Producto');
    }

    public function index() {
        // Obtener parámetros de paginación
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $por_pagina = 12;

        // Debug
        error_log("Usuario ID en sesión: " . $_SESSION['usuario_id']);

        // Obtener favoritos del usuario
        $favoritos = $this->favoritoModel->getFavoritosUsuario(
            $_SESSION['usuario_id'],
            $pagina,
            $por_pagina
        );

        // Debug
        error_log("Favoritos obtenidos: " . print_r($favoritos, true));

        $this->view('favoritos/index', [
            'title' => 'Mis Favoritos',
            'description' => 'Lista de productos favoritos',
            'favoritos' => $favoritos['items'],
            'total' => $favoritos['total'],
            'paginas' => $favoritos['paginas'],
            'pagina_actual' => $pagina
        ]);
    }

    public function toggle() {
        if (!$this->isAuthenticated()) {
            $this->jsonResponse(['success' => false, 'message' => 'Debes iniciar sesión']);
            return;
        }

        // Obtener datos JSON del cuerpo de la petición
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $producto_id = $data['producto_id'] ?? null;

        if (!$producto_id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID de producto no especificado']);
            return;
        }

        $resultado = $this->productoModel->toggleFavorito($_SESSION['usuario_id'], $producto_id);
        
        $this->jsonResponse([
            'success' => true,
            'isFavorito' => $resultado,
            'message' => $resultado ? 'Producto añadido a favoritos' : 'Producto eliminado de favoritos'
        ]);
    }

    public function eliminar($id) {
        if (!$this->isAuthenticated()) {
            $this->redirect('auth/login');
            return;
        }

        if ($this->favoritoModel->eliminar($_SESSION['usuario_id'], $id)) {
            SessionHelper::setFlash('success', 'Producto eliminado de favoritos');
        } else {
            SessionHelper::setFlash('error', 'Error al eliminar el producto de favoritos');
        }
        
        $this->redirect('favoritos');
    }
} 