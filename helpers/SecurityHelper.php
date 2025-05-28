<?php
class SecurityHelper {
    // Generar hash de contraseña
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => HASH_COST]);
    }

    // Verificar contraseña
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    // Generar token CSRF
    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // Verificar token CSRF
    public static function verifyCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    // Sanitizar entrada
    public static function sanitizeInput($data) {
        if (is_array($data)) {
            return array_map([self::class, 'sanitizeInput'], $data);
        }
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    // Generar token aleatorio
    public static function generateRandomToken($length = 32) {
        return bin2hex(random_bytes($length));
    }

    // Validar token de recuperación de contraseña
    public static function validateResetToken($token, $expiry) {
        return !empty($token) && !empty($expiry) && strtotime($expiry) > time();
    }

    // Prevenir XSS
    public static function preventXSS($value) {
        return strip_tags($value);
    }

    // Validar URL segura
    public static function isSecureURL($url) {
        return filter_var($url, FILTER_VALIDATE_URL) && 
               parse_url($url, PHP_URL_SCHEME) === 'https';
    }

    // Generar ID de sesión seguro
    public static function regenerateSession() {
        session_regenerate_id(true);
    }

    // Validar dirección IP
    public static function isValidIP($ip) {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    // Obtener IP real del cliente
    public static function getClientIP() {
        $ipAddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipAddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }
        return $ipAddress;
    }

    // Validar origen de la solicitud
    public static function validateOrigin() {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            $allowedOrigins = [
                'https://tudominio.com',
                'https://www.tudominio.com'
            ];
            return in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins);
        }
        return false;
    }

    // Establecer headers de seguridad
    public static function setSecurityHeaders() {
        header("X-XSS-Protection: 1; mode=block");
        header("X-Content-Type-Options: nosniff");
        header("X-Frame-Options: SAMEORIGIN");
        header("Referrer-Policy: strict-origin-when-cross-origin");
        header("Content-Security-Policy: default-src 'self'");
    }
} 