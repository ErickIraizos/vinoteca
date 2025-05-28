<?php
class PedidoController extends Controller {
    private $pedidoModel;
    private $carritoModel;
    private $usuarioModel;
    private $inventarioModel;
    private $emailHelper;

    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        
        $this->pedidoModel = $this->loadModel('Pedido');
        $this->carritoModel = $this->loadModel('Carrito');
        $this->usuarioModel = $this->loadModel('Usuario');
        $this->inventarioModel = $this->loadModel('Inventario');
        $this->emailHelper = $this->loadModel('Email');
    }

    public function crear() {
        if (!$this->isPost()) {
            $this->redirect('carrito/checkout');
        }

        // Obtener carrito actual
        $carrito = $this->carritoModel->getCarrito($_SESSION['usuario_id']);
        if (empty($carrito['items'])) {
            $this->redirect('carrito');
        }

        // Verificar stock antes de proceder
        foreach ($carrito['items'] as $item) {
            $stock = $this->inventarioModel->getStock($item['producto_id']);
            if ($stock < $item['cantidad']) {
                SessionHelper::setFlash('error', 'Algunos productos no tienen suficiente stock');
                $this->redirect('carrito/checkout');
            }
        }

        // Datos del pedido
        $data = [
            'usuario_id' => $_SESSION['usuario_id'],
            'direccion_id' => $_POST['direccion_id'],
            'metodo_pago' => $_POST['metodo_pago'],
            'total' => $carrito['total'],
            'estado' => 'pendiente',
            'items' => $carrito['items']
        ];

        // Validar dirección
        $direccion = $this->usuarioModel->getDireccion($data['direccion_id']);
        if (!$direccion || $direccion['usuario_id'] !== $_SESSION['usuario_id']) {
            SessionHelper::setFlash('error', 'Dirección de envío inválida');
            $this->redirect('carrito/checkout');
        }

        // Validar método de pago
        if (!in_array($data['metodo_pago'], ['tarjeta', 'transferencia', 'paypal'])) {
            SessionHelper::setFlash('error', 'Método de pago inválido');
            $this->redirect('carrito/checkout');
        }

        // Crear pedido
        $pedidoId = $this->pedidoModel->crear($data);
        
        if ($pedidoId) {
            // Actualizar stock
            foreach ($carrito['items'] as $item) {
                $this->inventarioModel->reducirStock(
                    $item['producto_id'],
                    $item['cantidad']
                );
            }

            // Vaciar carrito
            $this->carritoModel->vaciar($_SESSION['usuario_id']);

            // Enviar email de confirmación
            $this->emailHelper->enviarConfirmacionPedido(
                $_SESSION['email'],
                $pedidoId,
                $data
            );

            // Redirigir a página de éxito
            $this->redirect('pedido/exito/' . $pedidoId);
        } else {
            SessionHelper::setFlash('error', 'Error al procesar el pedido');
            $this->redirect('carrito/checkout');
        }
    }

    public function exito($id) {
        $pedido = $this->pedidoModel->getDetalle($id);
        
        if (!$pedido || $pedido['usuario_id'] !== $_SESSION['usuario_id']) {
            $this->redirect('perfil/pedidos');
        }

        $this->view('pedido/exito', [
            'pedido' => $pedido,
            'title' => 'Pedido Confirmado',
            'description' => 'Tu pedido ha sido procesado correctamente'
        ]);
    }

    public function seguimiento($id) {
        $pedido = $this->pedidoModel->getDetalle($id);
        
        if (!$pedido || $pedido['usuario_id'] !== $_SESSION['usuario_id']) {
            $this->redirect('perfil/pedidos');
        }

        $historial = $this->pedidoModel->getHistorial($id);

        $this->view('pedido/seguimiento', [
            'pedido' => $pedido,
            'historial' => $historial,
            'title' => 'Seguimiento del Pedido #' . $id
        ]);
    }

    public function cancelar($id) {
        $pedido = $this->pedidoModel->getDetalle($id);
        
        if (!$pedido || $pedido['usuario_id'] !== $_SESSION['usuario_id']) {
            $this->redirect('perfil/pedidos');
        }

        // Solo se pueden cancelar pedidos pendientes
        if ($pedido['estado'] !== 'pendiente') {
            SessionHelper::setFlash('error', 'No se puede cancelar este pedido');
            $this->redirect('perfil/pedido/' . $id);
        }

        if ($this->pedidoModel->cancelar($id)) {
            // Restaurar stock
            foreach ($pedido['items'] as $item) {
                $this->inventarioModel->aumentarStock(
                    $item['producto_id'],
                    $item['cantidad']
                );
            }

            // Enviar email de cancelación
            $this->emailHelper->enviarCancelacionPedido(
                $_SESSION['email'],
                $id
            );

            SessionHelper::setFlash('success', 'Pedido cancelado correctamente');
        } else {
            SessionHelper::setFlash('error', 'Error al cancelar el pedido');
        }

        $this->redirect('perfil/pedido/' . $id);
    }

    public function factura($id) {
        $pedido = $this->pedidoModel->getDetalle($id);
        
        if (!$pedido || $pedido['usuario_id'] !== $_SESSION['usuario_id']) {
            $this->redirect('perfil/pedidos');
        }

        // Generar factura PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Encabezado
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'FACTURA', 0, 1, 'C');
        $pdf->Cell(0, 10, 'Pedido #' . $pedido['pedido_id'], 0, 1, 'C');
        
        // Datos de la empresa
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, EMPRESA_NOMBRE, 0, 1);
        $pdf->Cell(0, 10, EMPRESA_DIRECCION, 0, 1);
        $pdf->Cell(0, 10, 'CIF: ' . EMPRESA_CIF, 0, 1);
        
        // Datos del cliente
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Cliente:', 0, 1);
        $pdf->Cell(0, 10, $pedido['usuario_nombre'], 0, 1);
        $pdf->Cell(0, 10, $pedido['direccion_envio'], 0, 1);
        
        // Detalles del pedido
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(90, 10, 'Producto', 1);
        $pdf->Cell(30, 10, 'Cantidad', 1);
        $pdf->Cell(35, 10, 'Precio', 1);
        $pdf->Cell(35, 10, 'Total', 1);
        $pdf->Ln();
        
        $pdf->SetFont('Arial', '', 12);
        foreach ($pedido['items'] as $item) {
            $pdf->Cell(90, 10, $item['nombre'], 1);
            $pdf->Cell(30, 10, $item['cantidad'], 1);
            $pdf->Cell(35, 10, number_format($item['precio'], 2) . ' €', 1);
            $pdf->Cell(35, 10, number_format($item['subtotal'], 2) . ' €', 1);
            $pdf->Ln();
        }
        
        // Total
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(155, 10, 'Total:', 1);
        $pdf->Cell(35, 10, number_format($pedido['total'], 2) . ' €', 1);
        
        // Pie de página
        $pdf->SetY(-50);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 10, 'Fecha: ' . date('d/m/Y', strtotime($pedido['fecha_creacion'])), 0, 1);
        $pdf->Cell(0, 10, 'Gracias por su compra', 0, 1, 'C');

        // Descargar PDF
        $pdf->Output('Factura-' . $pedido['pedido_id'] . '.pdf', 'D');
    }
} 