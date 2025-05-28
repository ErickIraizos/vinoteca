<?php
class PerfilController extends Controller {
    private $usuarioModel;
    private $pedidoModel;
    private $direccionModel;
    private $favoritoModel;

    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        
        $this->usuarioModel = $this->loadModel('Usuario');
        $this->pedidoModel = $this->loadModel('Pedido');
        $this->direccionModel = $this->loadModel('Direccion');
        $this->favoritoModel = $this->loadModel('Favorito');
    }

    public function index() {
        $usuario = $this->usuarioModel->getDetalle($_SESSION['usuario_id']);
        
        $this->view('perfil/index', [
            'usuario' => $usuario,
            'title' => 'Mi Perfil',
            'description' => 'Gestiona tu información personal'
        ]);
    }

    public function actualizar() {
        if ($this->isPost()) {
            $data = [
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'direccion' => $_POST['direccion'],
                'telefono' => $_POST['telefono'],
                'fecha_nacimiento' => $_POST['fecha_nacimiento']
            ];

            // Validaciones
            $errores = [];
            
            if (!ValidationHelper::notEmpty($data['nombre'])) {
                $errores['nombre'] = 'El nombre es requerido';
            }

            if (!ValidationHelper::notEmpty($data['apellido'])) {
                $errores['apellido'] = 'El apellido es requerido';
            }

            if (!empty($data['fecha_nacimiento']) && !ValidationHelper::isValidDate($data['fecha_nacimiento'])) {
                $errores['fecha_nacimiento'] = 'Fecha de nacimiento inválida';
            }

            if (empty($errores)) {
                if ($this->usuarioModel->update($_SESSION['usuario_id'], $data)) {
                    SessionHelper::setFlash('success', 'Perfil actualizado correctamente');
                } else {
                    SessionHelper::setFlash('error', 'Error al actualizar el perfil');
                }
            } else {
                SessionHelper::set('errores', $errores);
                SessionHelper::set('old', $data);
            }
        }

        $this->redirect('perfil');
    }

    public function cambiarPassword() {
        if ($this->isPost()) {
            $actual = $_POST['password_actual'];
            $nueva = $_POST['password_nueva'];
            $confirmar = $_POST['confirmar_password'];

            $usuario = $this->usuarioModel->findById($_SESSION['usuario_id']);

            if (!SecurityHelper::verifyPassword($actual, $usuario['password'])) {
                SessionHelper::setFlash('error', 'La contraseña actual es incorrecta');
                $this->redirect('perfil');
            }

            if (!ValidationHelper::isStrongPassword($nueva)) {
                SessionHelper::setFlash('error', 'La nueva contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial');
                $this->redirect('perfil');
            }

            if ($nueva !== $confirmar) {
                SessionHelper::setFlash('error', 'Las contraseñas no coinciden');
                $this->redirect('perfil');
            }

            $this->usuarioModel->actualizarPassword(
                $_SESSION['usuario_id'],
                SecurityHelper::hashPassword($nueva)
            );

            SessionHelper::setFlash('success', 'Contraseña actualizada correctamente');
            $this->redirect('perfil');
        }
    }

    public function pedidos() {
        $pagina = $_GET['pagina'] ?? 1;
        $pedidos = $this->pedidoModel->getPedidosUsuario($_SESSION['usuario_id'], $pagina);

        $this->view('perfil/pedidos', [
            'pedidos' => $pedidos['items'],
            'total' => $pedidos['total'],
            'paginas' => $pedidos['paginas'],
            'pagina_actual' => $pagina,
            'title' => 'Mis Pedidos'
        ]);
    }

    public function pedidoDetalle($id) {
        $pedido = $this->pedidoModel->getDetalle($id);
        
        if (!$pedido || $pedido['usuario_id'] !== $_SESSION['usuario_id']) {
            $this->redirect('perfil/pedidos');
        }

        $this->view('perfil/pedido-detalle', [
            'pedido' => $pedido,
            'title' => 'Detalle del Pedido #' . $id
        ]);
    }

    public function direcciones() {
        $direcciones = $this->direccionModel->getDireccionesUsuario($_SESSION['usuario_id']);

        $this->view('perfil/direcciones', [
            'direcciones' => $direcciones,
            'title' => 'Mis Direcciones'
        ]);
    }

    public function direccionForm($id = null) {
        $direccion = null;
        if ($id) {
            $direccion = $this->direccionModel->findById($id);
            if (!$direccion || $direccion['usuario_id'] !== $_SESSION['usuario_id']) {
                $this->redirect('perfil/direcciones');
            }
        }

        if ($this->isPost()) {
            $data = [
                'usuario_id' => $_SESSION['usuario_id'],
                'nombre' => $_POST['nombre'],
                'direccion' => $_POST['direccion'],
                'codigo_postal' => $_POST['codigo_postal'],
                'ciudad' => $_POST['ciudad'],
                'provincia' => $_POST['provincia'],
                'telefono' => $_POST['telefono'],
                'predeterminada' => isset($_POST['predeterminada']) ? 1 : 0
            ];

            // Validaciones
            $errores = [];
            
            if (!ValidationHelper::notEmpty($data['nombre'])) {
                $errores['nombre'] = 'El nombre es requerido';
            }

            if (!ValidationHelper::notEmpty($data['direccion'])) {
                $errores['direccion'] = 'La dirección es requerida';
            }

            if (!ValidationHelper::isValidPostalCode($data['codigo_postal'])) {
                $errores['codigo_postal'] = 'Código postal inválido';
            }

            if (!ValidationHelper::notEmpty($data['ciudad'])) {
                $errores['ciudad'] = 'La ciudad es requerida';
            }

            if (!ValidationHelper::notEmpty($data['provincia'])) {
                $errores['provincia'] = 'La provincia es requerida';
            }

            if (!ValidationHelper::isValidPhone($data['telefono'])) {
                $errores['telefono'] = 'Teléfono inválido';
            }

            if (empty($errores)) {
                if ($id) {
                    $this->direccionModel->update($id, $data);
                    SessionHelper::setFlash('success', 'Dirección actualizada correctamente');
                } else {
                    $this->direccionModel->create($data);
                    SessionHelper::setFlash('success', 'Dirección agregada correctamente');
                }
                $this->redirect('perfil/direcciones');
            } else {
                SessionHelper::set('errores', $errores);
                SessionHelper::set('old', $data);
            }
        }

        $this->view('perfil/direccion-form', [
            'direccion' => $direccion,
            'title' => $direccion ? 'Editar Dirección' : 'Nueva Dirección',
            'errores' => SessionHelper::get('errores', []),
            'old' => SessionHelper::get('old', [])
        ]);

        SessionHelper::delete('errores');
        SessionHelper::delete('old');
    }

    public function eliminarDireccion($id) {
        $direccion = $this->direccionModel->findById($id);
        
        if ($direccion && $direccion['usuario_id'] === $_SESSION['usuario_id']) {
            $this->direccionModel->delete($id);
            SessionHelper::setFlash('success', 'Dirección eliminada correctamente');
        }

        $this->redirect('perfil/direcciones');
    }

    public function favoritos() {
        $pagina = $_GET['pagina'] ?? 1;
        $favoritos = $this->favoritoModel->getFavoritosUsuario($_SESSION['usuario_id'], $pagina);

        $this->view('perfil/favoritos', [
            'favoritos' => $favoritos['items'],
            'total' => $favoritos['total'],
            'paginas' => $favoritos['paginas'],
            'pagina_actual' => $pagina,
            'title' => 'Mis Favoritos'
        ]);
    }

    public function eliminarFavorito($id) {
        if ($this->isAjax()) {
            $resultado = $this->favoritoModel->eliminar($_SESSION['usuario_id'], $id);
            $this->jsonResponse([
                'success' => $resultado,
                'message' => $resultado ? 'Producto eliminado de favoritos' : 'Error al eliminar el producto'
            ]);
        } else {
            $this->favoritoModel->eliminar($_SESSION['usuario_id'], $id);
            SessionHelper::setFlash('success', 'Producto eliminado de favoritos');
            $this->redirect('perfil/favoritos');
        }
    }
} 