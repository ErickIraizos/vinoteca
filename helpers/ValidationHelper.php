<?php
class ValidationHelper {
    // Validar email
    public static function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Validar que un string no esté vacío
    public static function notEmpty($value) {
        return !empty(trim($value));
    }

    // Validar longitud mínima
    public static function minLength($value, $min) {
        return strlen(trim($value)) >= $min;
    }

    // Validar longitud máxima
    public static function maxLength($value, $max) {
        return strlen(trim($value)) <= $max;
    }

    // Validar que un valor sea numérico
    public static function isNumeric($value) {
        return is_numeric($value);
    }

    // Validar que un valor esté en un rango
    public static function inRange($value, $min, $max) {
        return $value >= $min && $value <= $max;
    }

    // Validar que un valor sea una fecha válida
    public static function isValidDate($date) {
        if (empty($date)) return true; // Fecha opcional
        
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    // Validar que un valor coincida con una expresión regular
    public static function matchesPattern($value, $pattern) {
        return preg_match($pattern, $value);
    }

    // Validar que un archivo sea una imagen válida
    public static function isValidImage($file) {
        if (!isset($file['type'])) return false;
        
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        return in_array($file['type'], $allowed);
    }

    // Validar tamaño máximo de archivo
    public static function maxFileSize($file, $maxSize) {
        return isset($file['size']) && $file['size'] <= $maxSize;
    }

    // Sanitizar string
    public static function sanitizeString($value) {
        return htmlspecialchars(strip_tags(trim($value)));
    }

    // Validar contraseña fuerte
    public static function isStrongPassword($password) {
        // Mínimo 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial
        return strlen($password) >= 8 &&
               preg_match('/[A-Z]/', $password) &&
               preg_match('/[a-z]/', $password) &&
               preg_match('/[0-9]/', $password) &&
               preg_match('/[^A-Za-z0-9]/', $password);
    }

    // Validar DNI español
    public static function isValidDNI($dni) {
        $letter = substr($dni, -1);
        $numbers = substr($dni, 0, -1);
        
        if (!preg_match('/^[0-9]{8}[A-Z]$/', $dni)) return false;
        
        $validLetters = "TRWAGMYFPDXBNJZSQVHLCKE";
        return $letter === $validLetters[intval($numbers) % 23];
    }

    // Validar teléfono español
    public static function isValidPhone($phone) {
        // Acepta formatos: +34 123 456 789, 123-456-789, 123456789
        return preg_match('/^(\+\d{1,3}\s?)?\d{3}[-\s]?\d{3}[-\s]?\d{3}$/', $phone);
    }

    // Validar código postal español
    public static function isValidPostalCode($cp) {
        return preg_match('/^(?:0[1-9]|[1-4]\d|5[0-2])\d{3}$/', $cp);
    }
} 