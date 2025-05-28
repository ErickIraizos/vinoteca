<?php
class HomeController extends Controller {
    private $productoModel;
    private $categoriaModel;
    private $resenaModel;
    private $newsletterModel;

    public function __construct() {
        parent::__construct();
        $this->productoModel = $this->loadModel('Producto');
        $this->categoriaModel = $this->loadModel('Categoria');
        $this->resenaModel = $this->loadModel('Resena');
        $this->newsletterModel = $this->loadModel('Newsletter');
    }

    public function index() {
        // Productos destacados para el carrusel
        $resultadosDestacados = $this->productoModel->getDestacados();
        $destacados = $resultadosDestacados['productos'];
        
        // Categorías principales
        $categorias = $this->categoriaModel->getCategoriasPrincipales();
        
        // Ofertas especiales (solo 3 últimas)
        $ofertas = $this->productoModel->getOfertas(3, 1);
        
        // Reseñas destacadas
        $resenas = $this->resenaModel->getResenasDestacadas(3);
        
        // Productos más vendidos
        $populares = $this->productoModel->getProductosPopulares();
        
        // Novedades
        $novedades = $this->productoModel->getNovedades();

        $this->view('home/index', [
            'destacados' => $destacados,
            'categorias' => $categorias,
            'ofertas' => $ofertas,
            'resenas' => $resenas,
            'populares' => $populares,
            'novedades' => $novedades,
            'title' => 'Bienvenido a Vinoteca Online',
            'description' => 'Tu tienda online de vinos y licores de calidad'
        ]);
    }

    public function buscar() {
        if (!$this->isAjax()) {
            $this->redirect('');
        }

        $termino = $_GET['q'] ?? '';
        $filtros = [
            'categoria_id' => $_GET['categoria'] ?? null,
            'precio_min' => $_GET['precio_min'] ?? null,
            'precio_max' => $_GET['precio_max'] ?? null,
            'grado_min' => $_GET['grado_min'] ?? null,
            'grado_max' => $_GET['grado_max'] ?? null,
            'pais' => $_GET['pais'] ?? null
        ];

        $resultados = $this->productoModel->buscar($termino, $filtros);
        
        $this->jsonResponse([
            'success' => true,
            'resultados' => $resultados
        ]);
    }

    public function newsletter() {
        if (!$this->isPost()) {
            $this->redirect('');
        }

        $email = $_POST['email'];

        // Validar email
        if (!ValidationHelper::isValidEmail($email)) {
            if ($this->isAjax()) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Email inválido'
                ]);
            } else {
                SessionHelper::setFlash('error', 'Email inválido');
                $this->redirect('');
            }
        }

        // Verificar si ya está suscrito
        if ($this->newsletterModel->emailExiste($email)) {
            if ($this->isAjax()) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Este email ya está suscrito'
                ]);
            } else {
                SessionHelper::setFlash('error', 'Este email ya está suscrito');
                $this->redirect('');
            }
        }

        // Suscribir
        if ($this->newsletterModel->suscribir($email)) {
            // Enviar email de bienvenida
            $this->emailHelper->enviarBienvenidaNewsletter($email);

            if ($this->isAjax()) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => '¡Gracias por suscribirte!'
                ]);
            } else {
                SessionHelper::setFlash('success', '¡Gracias por suscribirte!');
                $this->redirect('');
            }
        } else {
            if ($this->isAjax()) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Error al procesar la suscripción'
                ]);
            } else {
                SessionHelper::setFlash('error', 'Error al procesar la suscripción');
                $this->redirect('');
            }
        }
    }

    public function contacto() {
        if ($this->isPost()) {
            $data = [
                'nombre' => $_POST['nombre'],
                'email' => $_POST['email'],
                'asunto' => $_POST['asunto'],
                'mensaje' => $_POST['mensaje']
            ];

            // Validaciones
            $errores = [];
            
            if (!ValidationHelper::notEmpty($data['nombre'])) {
                $errores['nombre'] = 'El nombre es requerido';
            }

            if (!ValidationHelper::isValidEmail($data['email'])) {
                $errores['email'] = 'Email inválido';
            }

            if (!ValidationHelper::notEmpty($data['asunto'])) {
                $errores['asunto'] = 'El asunto es requerido';
            }

            if (!ValidationHelper::notEmpty($data['mensaje'])) {
                $errores['mensaje'] = 'El mensaje es requerido';
            }

            if (empty($errores)) {
                // Enviar email
                $this->emailHelper->enviarContacto($data);
                
                SessionHelper::setFlash('success', 'Mensaje enviado correctamente');
                $this->redirect('contacto');
            } else {
                SessionHelper::set('errores', $errores);
                SessionHelper::set('old', $data);
            }
        }

        $this->view('home/contacto', [
            'title' => 'Contacto',
            'description' => 'Contáctanos para cualquier consulta',
            'errores' => SessionHelper::get('errores', []),
            'old' => SessionHelper::get('old', [])
        ]);

        SessionHelper::delete('errores');
        SessionHelper::delete('old');
    }

    public function nosotros() {
        $this->view('home/nosotros', [
            'title' => 'Sobre Nosotros - Vinoteca Online',
            'description' => 'Conoce más sobre nuestra tienda de vinos y licores'
        ]);
    }

    public function legal() {
        $pagina = $_GET['p'] ?? 'privacidad';
        
        switch ($pagina) {
            case 'privacidad':
                $title = 'Política de Privacidad';
                $view = 'privacidad';
                break;
            case 'cookies':
                $title = 'Política de Cookies';
                $view = 'cookies';
                break;
            case 'terminos':
                $title = 'Términos y Condiciones';
                $view = 'terminos';
                break;
            default:
                $this->redirect('');
        }

        $this->view('home/legal/' . $view, [
            'title' => $title,
            'description' => $title . ' de ' . EMPRESA_NOMBRE
        ]);
    }
}
?> 