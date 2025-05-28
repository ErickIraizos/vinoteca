<?php
class SessionHelper {
    // Iniciar sesión si no está iniciada
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Establecer un valor en la sesión
    public static function set($key, $value) {
        self::init();
        $_SESSION[$key] = $value;
    }

    // Obtener un valor de la sesión
    public static function get($key, $default = null) {
        self::init();
        return $_SESSION[$key] ?? $default;
    }

    // Eliminar un valor de la sesión
    public static function delete($key) {
        self::init();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    // Verificar si existe un valor en la sesión
    public static function exists($key) {
        return isset($_SESSION[$key]);
    }

    // Obtener todos los datos de la sesión
    public static function all() {
        return $_SESSION;
    }

    // Destruir la sesión
    public static function destroy() {
        session_destroy();
    }

    // Establecer un mensaje flash (mensaje que se muestra una vez y luego se elimina)
    public static function setFlash($key, $message) {
        self::init();
        $_SESSION['flash_messages'][$key] = $message;
    }

    // Verificar si existe un mensaje flash
    public static function hasFlash($key) {
        self::init();
        return isset($_SESSION['flash_messages'][$key]);
    }

    // Obtener y eliminar un mensaje flash
    public static function getFlash($key) {
        self::init();
        if (isset($_SESSION['flash_messages'][$key])) {
            $message = $_SESSION['flash_messages'][$key];
            unset($_SESSION['flash_messages'][$key]);
            return $message;
        }
        return null;
    }

    // Verificar si el usuario está autenticado
    public static function isAuthenticated() {
        return isset($_SESSION['usuario_id']);
    }

    // Verificar si el usuario es administrador
    public static function isAdmin() {
        return self::isAuthenticated() && isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
    }

    // Regenerar el ID de sesión
    public static function regenerate() {
        session_regenerate_id(true);
    }
} 