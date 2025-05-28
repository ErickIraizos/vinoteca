<?php
require_once 'config/database.php';
require_once 'helpers/SessionHelper.php';

class Controller {
    protected $db;
    protected $view;
    protected $model;

    public function __construct() {
        SessionHelper::init();
        $database = new Database();
        $this->db = $database->getConnection();
        $this->loadHelpers();
    }

    protected function loadModel($model) {
        $modelName = ucfirst($model) . 'Model';
        $modelFile = 'models/' . $modelName . '.php';
        
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $modelName($this->db);
        }
        return null;
    }

    protected function view($view, $data = []) {
        $viewFile = 'views/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            error_log("Vista no encontrada: " . $viewFile);
            echo "Error: Vista no encontrada - " . $viewFile;
            echo "<pre>";
            debug_print_backtrace();
            echo "</pre>";
            exit();
        }

        try {
            // Cargar las categorías para el menú
            $categoriaModel = $this->loadModel('Categoria');
            if ($categoriaModel) {
                $data['menuCategorias'] = $categoriaModel->getMenuCategorias();
            }
            
            // Extraer variables para la vista
            extract($data);
            
            // Si la vista es admin, no incluir header/footer global
            if (strpos($view, 'admin/') === 0) {
                require_once $viewFile;
            } else {
                require_once 'views/templates/header.php';
                require_once $viewFile;
                require_once 'views/templates/footer.php';
            }
        } catch (Exception $e) {
            error_log("Error al cargar la vista: " . $e->getMessage());
            echo "Error al cargar la vista: " . $e->getMessage();
            echo "<pre>";
            debug_print_backtrace();
            echo "</pre>";
            exit();
        }
    }

    protected function loadHelpers() {
        $helpers = ['SessionHelper', 'ValidationHelper', 'SecurityHelper'];
        foreach ($helpers as $helper) {
            $helperFile = 'helpers/' . $helper . '.php';
            if (file_exists($helperFile)) {
                require_once $helperFile;
            }
        }
    }

    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit();
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    protected function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    protected function isAuthenticated() {
        return isset($_SESSION['usuario_id']);
    }

    protected function requireAuth() {
        if (!$this->isAuthenticated()) {
            SessionHelper::setFlash('error', 'Debes iniciar sesión para acceder a esta página');
            $this->redirect('auth/login');
        }
    }

    protected function requireAdmin() {
        if (!$this->isAuthenticated() || $_SESSION['rol'] !== 'admin') {
            $this->redirect('error/forbidden');
        }
    }
}
?> 