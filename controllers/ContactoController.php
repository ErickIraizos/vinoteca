<?php
class ContactoController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->view('contacto/index', [
            'title' => 'Contacto - Vinoteca Online',
            'description' => 'Contáctanos para cualquier consulta sobre nuestros productos o servicios'
        ]);
    }

    public function enviar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener datos del formulario
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $asunto = $_POST['asunto'] ?? '';
            $mensaje = $_POST['mensaje'] ?? '';
            
            // Aquí puedes agregar la lógica para enviar el email
            // Por ahora solo redireccionamos con un mensaje de éxito
            $_SESSION['mensaje'] = [
                'tipo' => 'success',
                'texto' => 'Mensaje enviado correctamente. Nos pondremos en contacto contigo pronto.'
            ];
            
            $this->redirect('contacto');
        }
    }
}
?> 