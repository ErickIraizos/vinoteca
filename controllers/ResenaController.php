<?php
class ResenaController extends Controller {
    private $resenaModel;
    private $productoModel;
    private $pedidoModel;

    public function __construct() {
        parent::__construct();
        $this->resenaModel = $this->loadModel('Resena');
        $this->productoModel = $this->loadModel('Producto');
        $this->pedidoModel = $this->loadModel('Pedido');
    }

    public function crear() {
        if (!$this->isPost() || !$this->isAuthenticated()) {
            $this->redirect('auth/login');
        }

        $data = [
            'producto_id' => $_POST['producto_id'],
            'usuario_id' => $_SESSION['usuario_id'],
            'calificacion' => $_POST['calificacion'],
            'comentario' => $_POST['comentario'],
            'titulo' => $_POST['titulo'] ?? null
        ];

        // Validaciones
        $errores = [];
        
        if (!$data['producto_id'] || !$this->productoModel->findById($data['producto_id'])) {
            $errores['producto'] = 'Producto inválido';
        }

        if (!$this->pedidoModel->haCompradoProducto($data['usuario_id'], $data['producto_id'])) {
            $errores['compra'] = 'Solo puedes reseñar productos que hayas comprado';
        }

        if (!is_numeric($data['calificacion']) || $data['calificacion'] < 1 || $data['calificacion'] > 5) {
            $errores['calificacion'] = 'Calificación inválida';
        }

        if (!ValidationHelper::notEmpty($data['comentario'])) {
            $errores['comentario'] = 'El comentario es requerido';
        }

        if (!empty($data['titulo']) && !ValidationHelper::maxLength($data['titulo'], 100)) {
            $errores['titulo'] = 'El título no puede tener más de 100 caracteres';
        }

        if (empty($errores)) {
            if ($this->resenaModel->crearResena($data)) {
                if ($this->isAjax()) {
                    $this->jsonResponse([
                        'success' => true,
                        'message' => '¡Gracias por tu reseña!'
                    ]);
                } else {
                    SessionHelper::setFlash('success', '¡Gracias por tu reseña!');
                    $this->redirect('producto/detalle/' . $data['producto_id']);
                }
            } else {
                if ($this->isAjax()) {
                    $this->jsonResponse([
                        'success' => false,
                        'message' => 'Error al publicar la reseña'
                    ]);
                } else {
                    SessionHelper::setFlash('error', 'Error al publicar la reseña');
                    $this->redirect('producto/detalle/' . $data['producto_id']);
                }
            }
        } else {
            if ($this->isAjax()) {
                $this->jsonResponse([
                    'success' => false,
                    'errors' => $errores
                ]);
            } else {
                SessionHelper::set('errores', $errores);
                SessionHelper::set('old', $data);
                $this->redirect('producto/detalle/' . $data['producto_id']);
            }
        }
    }

    public function editar($id) {
        if (!$this->isAuthenticated()) {
            $this->redirect('auth/login');
        }

        $resena = $this->resenaModel->findById($id);
        if (!$resena || $resena['usuario_id'] !== $_SESSION['usuario_id']) {
            $this->redirect('perfil/resenas');
        }

        if ($this->isPost()) {
            $data = [
                'calificacion' => $_POST['calificacion'],
                'comentario' => $_POST['comentario'],
                'titulo' => $_POST['titulo'] ?? $resena['titulo']
            ];

            // Validaciones
            $errores = [];

            if (!is_numeric($data['calificacion']) || $data['calificacion'] < 1 || $data['calificacion'] > 5) {
                $errores['calificacion'] = 'Calificación inválida';
            }

            if (!ValidationHelper::notEmpty($data['comentario'])) {
                $errores['comentario'] = 'El comentario es requerido';
            }

            if (!empty($data['titulo']) && !ValidationHelper::maxLength($data['titulo'], 100)) {
                $errores['titulo'] = 'El título no puede tener más de 100 caracteres';
            }

            if (empty($errores)) {
                if ($this->resenaModel->actualizar($id, $data)) {
                    SessionHelper::setFlash('success', 'Reseña actualizada correctamente');
                    $this->redirect('producto/detalle/' . $resena['producto_id']);
                } else {
                    SessionHelper::setFlash('error', 'Error al actualizar la reseña');
                }
            } else {
                SessionHelper::set('errores', $errores);
                SessionHelper::set('old', $data);
            }
        }

        $this->view('resena/editar', [
            'resena' => $resena,
            'producto' => $this->productoModel->findById($resena['producto_id']),
            'errores' => SessionHelper::get('errores', []),
            'old' => SessionHelper::get('old', []),
            'title' => 'Editar Reseña'
        ]);

        SessionHelper::delete('errores');
        SessionHelper::delete('old');
    }

    public function eliminar($id) {
        if (!$this->isAuthenticated()) {
            $this->redirect('auth/login');
        }

        $resena = $this->resenaModel->findById($id);
        if (!$resena || $resena['usuario_id'] !== $_SESSION['usuario_id']) {
            $this->redirect('perfil/resenas');
        }

        if ($this->resenaModel->eliminar($id)) {
            if ($this->isAjax()) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Reseña eliminada correctamente'
                ]);
            } else {
                SessionHelper::setFlash('success', 'Reseña eliminada correctamente');
                $this->redirect('producto/detalle/' . $resena['producto_id']);
            }
        } else {
            if ($this->isAjax()) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Error al eliminar la reseña'
                ]);
            } else {
                SessionHelper::setFlash('error', 'Error al eliminar la reseña');
                $this->redirect('producto/detalle/' . $resena['producto_id']);
            }
        }
    }

    public function votar($id) {
        if (!$this->isAuthenticated()) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Debes iniciar sesión para votar'
            ]);
        }

        $tipo = $_POST['tipo'] ?? 'util';
        if (!in_array($tipo, ['util', 'no_util'])) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Tipo de voto inválido'
            ]);
        }

        $resultado = $this->resenaModel->votar($id, $_SESSION['usuario_id'], $tipo);
        
        $this->jsonResponse([
            'success' => true,
            'votos_utiles' => $resultado['votos_utiles'],
            'votos_no_utiles' => $resultado['votos_no_utiles']
        ]);
    }

    public function reportar($id) {
        if (!$this->isAuthenticated()) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Debes iniciar sesión para reportar'
            ]);
        }

        $motivo = $_POST['motivo'] ?? '';
        if (!ValidationHelper::notEmpty($motivo)) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Debes especificar un motivo'
            ]);
        }

        if ($this->resenaModel->reportar($id, $_SESSION['usuario_id'], $motivo)) {
            $this->jsonResponse([
                'success' => true,
                'message' => 'Reseña reportada correctamente'
            ]);
        } else {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Error al reportar la reseña'
            ]);
        }
    }
} 