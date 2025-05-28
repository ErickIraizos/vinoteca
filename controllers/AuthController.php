<?php
class AuthController extends Controller {
    private $usuarioModel;
    private $emailHelper;

    public function __construct() {
        parent::__construct();
        $this->usuarioModel = $this->loadModel('Usuario');
        $this->emailHelper = $this->loadModel('Email');
    }

    public function login() {
        if ($this->isPost()) {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $recordar = isset($_POST['recordar']);

            // Validación básica
            if (empty($email) || empty($password)) {
                SessionHelper::setFlash('error', 'Por favor, completa todos los campos');
                $this->view('auth/login');
                return;
            }

            // Intentar autenticar al usuario
            $usuario = $this->usuarioModel->getByEmail($email);
            
            if ($usuario && password_verify($password, $usuario['password'])) {
                // Verificar si la cuenta está activa
                if (!$usuario['activo']) {
                    SessionHelper::setFlash('error', 'Tu cuenta está desactivada. Por favor, contacta con soporte.');
                    $this->view('auth/login');
                    return;
                }

                // Iniciar sesión
                $_SESSION['usuario_id'] = $usuario['usuario_id'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                $_SESSION['is_admin'] = ($usuario['rol'] === 'admin');

                // Si el usuario es admin, redirigir a la página principal pública
                if ($usuario['rol'] === 'admin') {
                    $this->redirect('');
                    return;
                }

                // Si no es admin, redirigir a la página principal
                $this->redirect('');
                return;
            }

            // Si la autenticación falla
            SessionHelper::setFlash('error', 'Email o contraseña incorrectos');
            $this->view('auth/login');
            return;
        }

        // Si no es POST, mostrar el formulario de login
        $this->view('auth/login');
    }

    public function logout() {
        // Destruir todas las variables de sesión
        session_unset();
        session_destroy();
        
        // Redirigir al login
        $this->redirect('auth/login');
    }

    public function registro() {
        if ($this->isPost()) {
            $data = [
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'nombre' => $_POST['nombre'] ?? '',
                'apellido' => $_POST['apellido'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null,
                'token_verificacion' => bin2hex(random_bytes(32))
            ];

            // Validar que el email no exista
            if ($this->usuarioModel->emailExiste($data['email'])) {
                SessionHelper::setFlash('error', 'El email ya está registrado');
                $this->view('auth/registro');
                return;
            }

            // Encriptar contraseña
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            // Crear usuario
            if ($this->usuarioModel->crear($data)) {
                SessionHelper::setFlash('success', 'Registro exitoso. Por favor, inicia sesión.');
                $this->redirect('auth/login');
                return;
            }

            SessionHelper::setFlash('error', 'Error al crear la cuenta');
            $this->view('auth/registro');
            return;
        }

        $this->view('auth/registro');
    }

    public function verificar($token) {
        $usuario = $this->usuarioModel->getByVerificationToken($token);
        
        if ($usuario) {
            $this->usuarioModel->verificarEmail($usuario['usuario_id']);
            SessionHelper::setFlash('success', 'Email verificado correctamente. Ya puedes iniciar sesión');
        } else {
            SessionHelper::setFlash('error', 'Token de verificación inválido');
        }

        $this->redirect('auth/login');
    }

    public function recuperar() {
        if ($this->isPost()) {
            $email = $_POST['email'];
            $usuario = $this->usuarioModel->getByEmail($email);

            if ($usuario) {
                $token = SecurityHelper::generateRandomToken();
                $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                $this->usuarioModel->guardarTokenRecuperacion($usuario['usuario_id'], $token, $expiry);
                $this->emailHelper->enviarRecuperacion($email, $token);
            }

            // Siempre mostrar el mismo mensaje para no revelar si el email existe
            SessionHelper::setFlash('success', 'Si el email existe en nuestra base de datos, recibirás instrucciones para recuperar tu contraseña');
            $this->redirect('auth/login');
        }

        $this->view('auth/recuperar', [
            'title' => 'Recuperar Contraseña',
            'description' => 'Recupera el acceso a tu cuenta'
        ]);
    }

    public function resetear($token) {
        $usuario = $this->usuarioModel->getByResetToken($token);
        
        if (!$usuario || !SecurityHelper::validateResetToken($token, $usuario['reset_token_expiry'])) {
            SessionHelper::setFlash('error', 'Token inválido o expirado');
            $this->redirect('auth/login');
        }

        if ($this->isPost()) {
            $password = $_POST['password'];
            $confirmar_password = $_POST['confirmar_password'];

            if (!ValidationHelper::isStrongPassword($password)) {
                SessionHelper::setFlash('error', 'La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial');
                $this->redirect('auth/resetear/' . $token);
            }

            if ($password !== $confirmar_password) {
                SessionHelper::setFlash('error', 'Las contraseñas no coinciden');
                $this->redirect('auth/resetear/' . $token);
            }

            $this->usuarioModel->actualizarPassword(
                $usuario['usuario_id'],
                SecurityHelper::hashPassword($password)
            );

            SessionHelper::setFlash('success', 'Contraseña actualizada correctamente');
            $this->redirect('auth/login');
        }

        $this->view('auth/resetear', [
            'title' => 'Resetear Contraseña',
            'description' => 'Establece tu nueva contraseña',
            'token' => $token
        ]);
    }
} 