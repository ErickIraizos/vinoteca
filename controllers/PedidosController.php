<?php

class PedidosController extends Controller {
    private $pedidoModel;
    private $usuarioModel;

    public function __construct() {
        parent::__construct();
        
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . 'auth/login');
            exit();
        }

        $this->pedidoModel = $this->loadModel('Pedido');
        $this->usuarioModel = $this->loadModel('Usuario');
    }

    public function index() {
        // Obtener parámetros de paginación y filtros
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $estado = isset($_GET['estado']) ? $_GET['estado'] : '';
        $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
        $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
        
        // Obtener el ID del usuario de la sesión
        $usuario_id = $_SESSION['usuario_id'];

        // Obtener pedidos con paginación
        $por_pagina = 10;
        $pedidos = $this->pedidoModel->getPedidosPaginados($pagina, $por_pagina, $usuario_id);

        // Obtener estadísticas del usuario actual
        $estadisticas = [
            'total_pedidos' => $this->pedidoModel->getTotalPedidosUsuario($usuario_id),
            'pedidos_pendientes' => $this->pedidoModel->getTotalPedidosPorEstadoUsuario($usuario_id, 'pendiente'),
            'pedidos_completados' => $this->pedidoModel->getTotalPedidosPorEstadoUsuario($usuario_id, 'entregado'),
            'total_ventas' => $this->pedidoModel->getTotalVentasUsuario($usuario_id)
        ];

        // Cargar la vista
        $this->view('pedidos/index', [
            'title' => 'Mis Pedidos',
            'description' => 'Historial de mis pedidos',
            'pedidos' => $pedidos['items'],
            'total_paginas' => $pedidos['paginas'],
            'pagina_actual' => $pagina,
            'estadisticas' => $estadisticas,
            'filtros' => [
                'estado' => $estado,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin
            ]
        ]);
    }

    public function detalle($id) {
        error_log("Accediendo a detalle con ID: " . $id);
        
        if (!$id) {
            error_log("ID no especificado");
            $_SESSION['error'] = 'ID de pedido no especificado';
            header('Location: ' . BASE_URL . 'pedidos');
            exit();
        }

        // Obtener el pedido y sus detalles
        $pedido = $this->pedidoModel->getDetalle($id);
        error_log("Resultado de getDetalle: " . print_r($pedido, true));
        error_log("Usuario ID en sesión: " . $_SESSION['usuario_id']);

        // Verificar que el pedido exista y pertenezca al usuario
        if (!$pedido || intval($pedido['usuario_id']) !== intval($_SESSION['usuario_id'])) {
            error_log("Pedido no encontrado o no pertenece al usuario. Pedido usuario_id: " . 
                ($pedido ? $pedido['usuario_id'] : 'null') . 
                ", Session usuario_id: " . $_SESSION['usuario_id']);
            $_SESSION['error'] = 'Pedido no encontrado';
            header('Location: ' . BASE_URL . 'pedidos');
            exit();
        }

        // Obtener los detalles del pedido (productos)
        $detalles = $this->pedidoModel->getDetallesPedido($id);
        $pedido['detalles'] = $detalles;

        // Cargar la vista
        $this->view('pedidos/detalle', [
            'title' => 'Detalle del Pedido #' . $id,
            'description' => 'Información detallada del pedido',
            'pedido' => $pedido
        ]);
    }

    public function cancelar($id) {
        if (!$id) {
            $_SESSION['error'] = 'Pedido no especificado';
            header('Location: ' . BASE_URL . 'pedidos');
            exit();
        }

        $pedido = $this->pedidoModel->getDetalle($id);

        // Verificar que el pedido exista y pertenezca al usuario
        if (!$pedido || $pedido['usuario_id'] != $_SESSION['usuario_id']) {
            $_SESSION['error'] = 'Pedido no encontrado';
            header('Location: ' . BASE_URL . 'pedidos');
            exit();
        }

        // Verificar que el pedido pueda ser cancelado (solo si está pendiente)
        if ($pedido['estado'] !== 'pendiente') {
            $_SESSION['error'] = 'Este pedido no puede ser cancelado';
            header('Location: ' . BASE_URL . 'pedidos/detalle/' . $id);
            exit();
        }

        // Intentar cancelar el pedido
        if ($this->pedidoModel->cancelarPedido($id)) {
            $_SESSION['success'] = 'Pedido cancelado correctamente';
        } else {
            $_SESSION['error'] = 'Error al cancelar el pedido';
        }

        header('Location: ' . BASE_URL . 'pedidos/detalle/' . $id);
        exit();
    }

    public function actualizar_estado() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pedido_id = $_POST['pedido_id'] ?? null;
            $nuevo_estado = $_POST['estado'] ?? null;

            if ($pedido_id && $nuevo_estado) {
                if ($this->pedidoModel->actualizarEstado($pedido_id, $nuevo_estado)) {
                    $_SESSION['success'] = 'Estado actualizado correctamente';
                } else {
                    $_SESSION['error'] = 'Error al actualizar el estado';
                }
            }
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
} 