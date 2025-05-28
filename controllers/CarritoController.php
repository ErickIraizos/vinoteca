<?php
class CarritoController extends Controller {
    private $carritoModel;
    private $productoModel;
    private $inventarioModel;

    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->carritoModel = $this->loadModel('Carrito');
        $this->productoModel = $this->loadModel('Producto');
        $this->inventarioModel = $this->loadModel('Inventario');
    }

    public function index() {
        // Verificar si el usuario está autenticado
        if (!$this->isAuthenticated()) {
            $this->redirect('auth/login');
        }

        // Obtener items del carrito
        $items = $this->carritoModel->getItems($_SESSION['usuario_id']);
        $total = $this->carritoModel->getTotal($_SESSION['usuario_id']);

        $carrito = [
            'items' => $items,
            'total' => $total
        ];

        $this->view('carrito/index', [
            'title' => 'Mi Carrito',
            'description' => 'Revisa los productos en tu carrito',
            'carrito' => $carrito
        ]);
    }

    public function agregar() {
        if (!$this->isPost() || !$this->isAuthenticated()) {
            $this->jsonResponse(['success' => false, 'message' => 'Método no permitido']);
        }

        $producto_id = $_POST['producto_id'] ?? null;
        $cantidad = intval($_POST['cantidad'] ?? 1);

        if (!$producto_id || $cantidad <= 0) {
            $this->jsonResponse(['success' => false, 'message' => 'Datos inválidos']);
        }

        // Verificar stock
        $producto = $this->productoModel->findById($producto_id);
        if (!$producto || $producto['stock'] < $cantidad) {
            $this->jsonResponse(['success' => false, 'message' => 'Stock insuficiente']);
        }

        if ($this->carritoModel->agregarItem($_SESSION['usuario_id'], $producto_id, $cantidad)) {
            $this->jsonResponse([
                'success' => true,
                'message' => 'Producto agregado al carrito',
                'total' => $this->carritoModel->getTotal($_SESSION['usuario_id'])
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Error al agregar al carrito']);
        }
    }

    public function actualizar() {
        if (!$this->isPost() || !$this->isAuthenticated()) {
            $this->jsonResponse(['success' => false, 'message' => 'Método no permitido']);
        }

        $producto_id = $_POST['producto_id'] ?? null;
        $cantidad = intval($_POST['cantidad'] ?? 0);

        if (!$producto_id) {
            $this->jsonResponse(['success' => false, 'message' => 'Producto no especificado']);
        }

        if ($cantidad <= 0) {
            $this->carritoModel->eliminarItem($_SESSION['usuario_id'], $producto_id);
            $this->jsonResponse([
                'success' => true,
                'message' => 'Producto eliminado del carrito',
                'total' => $this->carritoModel->getTotal($_SESSION['usuario_id'])
            ]);
        }

        // Verificar stock
        $producto = $this->productoModel->findById($producto_id);
        if (!$producto || $producto['stock'] < $cantidad) {
            $this->jsonResponse(['success' => false, 'message' => 'Stock insuficiente']);
        }

        if ($this->carritoModel->actualizarCantidad($_SESSION['usuario_id'], $producto_id, $cantidad)) {
            $this->jsonResponse([
                'success' => true,
                'message' => 'Cantidad actualizada',
                'total' => $this->carritoModel->getTotal($_SESSION['usuario_id'])
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Error al actualizar cantidad']);
        }
    }

    public function eliminar() {
        if (!$this->isPost() || !$this->isAuthenticated()) {
            $this->jsonResponse(['success' => false, 'message' => 'Método no permitido']);
        }

        $producto_id = $_POST['producto_id'] ?? null;

        if (!$producto_id) {
            $this->jsonResponse(['success' => false, 'message' => 'Producto no especificado']);
        }

        if ($this->carritoModel->eliminarItem($_SESSION['usuario_id'], $producto_id)) {
            $this->jsonResponse([
                'success' => true,
                'message' => 'Producto eliminado del carrito',
                'total' => $this->carritoModel->getTotal($_SESSION['usuario_id'])
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Error al eliminar producto']);
        }
    }

    public function vaciar() {
        if (!$this->isPost() || !$this->isAuthenticated()) {
            $this->jsonResponse(['success' => false, 'message' => 'Método no permitido']);
        }

        if ($this->carritoModel->vaciar($_SESSION['usuario_id'])) {
            $this->jsonResponse([
                'success' => true,
                'message' => 'Carrito vaciado correctamente',
                'total' => 0
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Error al vaciar el carrito']);
        }
    }

    public function checkout() {
        if (!$this->isAuthenticated()) {
            SessionHelper::setFlash('error', 'Debes iniciar sesión para continuar');
            $this->redirect('auth/login');
        }

        $carrito = $this->carritoModel->getItems($_SESSION['usuario_id']);
        
        if (empty($carrito)) {
            $this->redirect('carrito');
        }

        // Verificar stock antes de proceder
        foreach ($carrito as $item) {
            $stock = $this->inventarioModel->getStock($item['producto_id']);
            if ($stock < $item['cantidad']) {
                SessionHelper::setFlash('error', 'Algunos productos no tienen suficiente stock');
                $this->redirect('carrito');
            }
        }

        // Vaciar el carrito después de verificar el stock
        if ($this->carritoModel->vaciar($_SESSION['usuario_id'])) {
            SessionHelper::setFlash('success', '¡Pedido realizado con éxito!');
            $this->redirect('perfil');
        } else {
            SessionHelper::setFlash('error', 'Error al procesar el pedido');
            $this->redirect('carrito');
        }
    }
} 