<?php
// Rutas del sistema
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP_PATH', ROOT_PATH . 'app' . DIRECTORY_SEPARATOR);
define('VIEWS_PATH', ROOT_PATH . 'views' . DIRECTORY_SEPARATOR);
define('CONTROLLERS_PATH', ROOT_PATH . 'controllers' . DIRECTORY_SEPARATOR);
define('MODELS_PATH', ROOT_PATH . 'models' . DIRECTORY_SEPARATOR);
define('PUBLIC_PATH', ROOT_PATH . 'public' . DIRECTORY_SEPARATOR);

// Configuración de la aplicación
define('BASE_URL', 'http://localhost/vino/');
define('SITE_NAME', 'Vinoteca Online');

// Información de la empresa
define('EMPRESA_NOMBRE', 'Vinoteca Online S.L.');
define('EMPRESA_DIRECCION', 'Calle del Vino 123, 28001 Madrid');
define('EMPRESA_CIF', 'B12345678');
define('EMPRESA_EMAIL', 'info@vinotecaonline.com');
define('EMPRESA_TELEFONO', '+34 912 345 678');

// Configuración de correo
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'tu_correo@gmail.com');
define('SMTP_PASS', 'tu_password');
define('SMTP_PORT', 587);

// Configuración de pagos (ejemplo con PayPal)
define('PAYPAL_CLIENT_ID', 'tu_client_id');
define('PAYPAL_CLIENT_SECRET', 'tu_client_secret');
define('PAYPAL_SANDBOX', true);

// Configuración de sesión
define('SESSION_LIFETIME', 3600); // 1 hora
define('COOKIE_LIFETIME', 604800); // 1 semana

// Configuración de imágenes
define('MAX_IMAGE_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
define('UPLOAD_PATH', 'public/uploads/');

// Configuración de paginación
define('ITEMS_PER_PAGE', 12);

// Configuración de seguridad
define('HASH_COST', 10); // Costo de hash para bcrypt