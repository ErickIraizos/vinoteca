<?php
class CheckoutController extends Controller {
    private $carritoModel;
    private $usuarioModel;
    private $pedidoModel;

    public function __construct() {
        parent::__construct();
        $this->carritoModel = $this->loadModel('Carrito');
        $this->usuarioModel = $this->loadModel('Usuario');
        $this->pedidoModel = $this->loadModel('Pedido');

        // Verificar si el usuario está autenticado
        if (!$this->isAuthenticated()) {
            $this->redirect('auth/login');
        }
    }

    public function index() {
        // Obtener items del carrito
        $items = $this->carritoModel->getItems($_SESSION['usuario_id']);
        
        // Obtener información del usuario
        $usuario = $this->usuarioModel->findById($_SESSION['usuario_id']);
        
        // Calcular totales
        $subtotal = 0;
        $iva = 0;
        $total = 0;
        
        foreach ($items as $item) {
            $precio = $item['precio_final'];
            $subtotal += $precio * $item['cantidad'];
        }
        
        $iva = $subtotal * 0.21; // 21% IVA
        $total = $subtotal + $iva;

        $this->view('checkout/index', [
            'title' => 'Finalizar Compra',
            'description' => 'Completa tu pedido',
            'items' => $items,
            'usuario' => $usuario,
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $total
        ]);
    }

    public function procesar() {
        if (!$this->isPost()) {
            $this->redirect('checkout');
        }

        // Validar datos del formulario
        $datos = [
            'nombre' => $_POST['nombre'] ?? '',
            'apellidos' => $_POST['apellidos'] ?? '',
            'email' => $_POST['email'] ?? '',
            'telefono' => $_POST['telefono'] ?? '',
            'direccion' => $_POST['direccion'] ?? '',
            'ciudad' => $_POST['ciudad'] ?? '',
            'codigo_postal' => $_POST['codigo_postal'] ?? '',
            'metodo_pago' => $_POST['metodo_pago'] ?? ''
        ];

        // Validar que todos los campos estén completos
        foreach ($datos as $campo => $valor) {
            if (empty($valor)) {
                SessionHelper::setFlash('error', 'Por favor, completa todos los campos');
                $this->redirect('checkout');
            }
        }

        // Validar método de pago
        if (!in_array($datos['metodo_pago'], ['tarjeta', 'efectivo', 'transferencia'])) {
            SessionHelper::setFlash('error', 'Método de pago no válido');
            $this->redirect('checkout');
        }

        // Obtener items del carrito
        $carrito = $this->carritoModel->getItems($_SESSION['usuario_id']);
        $total = $this->carritoModel->getTotal($_SESSION['usuario_id']);

        if (empty($carrito)) {
            SessionHelper::setFlash('error', 'El carrito está vacío');
            $this->redirect('carrito');
        }

        // Verificar stock antes de proceder
        foreach ($carrito as $item) {
            if ($item['stock'] < $item['cantidad']) {
                SessionHelper::setFlash('error', 'Algunos productos no tienen suficiente stock');
                $this->redirect('checkout');
            }
        }

        // Formatear la dirección de envío
        $direccion_envio = $datos['direccion'] . "\n" .
                          $datos['ciudad'] . " " . $datos['codigo_postal'] . "\n" .
                          "Tel: " . $datos['telefono'] . "\n" .
                          "Email: " . $datos['email'];

        // Preparar datos del pedido
        $pedido = [
            'usuario_id' => $_SESSION['usuario_id'],
            'direccion_envio' => $direccion_envio,
            'estado' => 'pendiente',
            'metodo_pago' => $datos['metodo_pago']
        ];

        error_log("Datos del pedido a crear: " . print_r($pedido, true));
        error_log("Items del carrito: " . print_r($carrito, true));

        // Crear el pedido
        $pedido_id = $this->pedidoModel->crear($pedido);

        if ($pedido_id) {
            error_log("Pedido creado con ID: " . $pedido_id);
            
            // Agregar items al pedido
            $errores_items = [];
            foreach ($carrito as $item) {
                if (!$this->pedidoModel->agregarItem($pedido_id, $item)) {
                    $errores_items[] = "Error al agregar el producto {$item['nombre']}";
                }
            }

            if (!empty($errores_items)) {
                error_log("Errores al agregar items: " . implode(", ", $errores_items));
                SessionHelper::setFlash('error', 'Error al procesar algunos productos del pedido');
                $this->redirect('checkout');
            }

            // Vaciar el carrito
            $this->carritoModel->vaciar($_SESSION['usuario_id']);

            SessionHelper::setFlash('success', '¡Pedido realizado con éxito!');
            $this->redirect('pedidos?pedido_id=' . $pedido_id . '&exito=1');
        } else {
            error_log("Error al crear el pedido - pedido_id es false");
            SessionHelper::setFlash('error', 'Error al procesar el pedido');
            $this->redirect('checkout');
        }
    }
} 