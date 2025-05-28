<?php
session_start();

// Configuración
require_once 'config/database.php';
require_once 'config/config.php';
require_once 'models/Model.php';

// Función de debug
function debug($value) {
    error_log(print_r($value, true));
}

// Autoload de clases
spl_autoload_register(function ($class) {
    $controllerFile = 'controllers/' . $class . '.php';
    $modelFile = 'models/' . $class . '.php';
    $helperFile = 'helpers/' . $class . '.php';

    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        return;
    }
    if (file_exists($modelFile)) {
        require_once $modelFile;
        return;
    }
    if (file_exists($helperFile)) {
        require_once $helperFile;
        return;
    }
});

// Router básico
$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);

// Redirección especial para paneladmin
if ($url === 'paneladmin') {
    $url = 'admin/paneladmin';
}

$url = explode('/', $url);

// --- INICIO PATCH PROVEEDORES ---
// Si la ruta es admin/proveedores, usar ProveedorController
if (strtolower($url[0]) === 'admin' && isset($url[1]) && strtolower($url[1]) === 'proveedores') {
    $controllerName = 'ProveedorController';
    $methodName = isset($url[2]) ? $url[2] : 'index';
    $params = array_slice($url, 3);
}
// --- INICIO PATCH PROMOCIONES PRODUCTOS ---
else if (
    strtolower($url[0]) === 'admin' &&
    isset($url[1]) && strtolower($url[1]) === 'promociones' &&
    isset($url[2]) && strtolower($url[2]) === 'productos' &&
    isset($url[3])
) {
    $controllerName = 'AdminController';
    $methodName = 'promocionesProductos';
    $params = [$url[3]];
}
// --- FIN PATCH PROMOCIONES PRODUCTOS ---
else {
    // Determinar el controlador
    $controllerName = ucfirst(strtolower($url[0])) . 'Controller';
    $methodName = isset($url[1]) ? $url[1] : 'index';
    $params = array_slice($url, 2);
}
// --- FIN PATCH PROVEEDORES ---

// Debug información
debug([
    'URL' => $url,
    'Controller' => $controllerName,
    'Method' => $methodName,
    'Params' => $params,
    'REQUEST_URI' => $_SERVER['REQUEST_URI'],
    'SCRIPT_NAME' => $_SERVER['SCRIPT_NAME'],
    'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT']
]);

try {
    $controllerFile = 'controllers/' . $controllerName . '.php';
    debug("Buscando controlador: " . $controllerFile);
    
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        
        if (class_exists($controllerName)) {
            $controller = new $controllerName();
            
            if (method_exists($controller, $methodName)) {
                debug("Ejecutando: {$controllerName}->{$methodName}()");
                call_user_func_array([$controller, $methodName], $params);
            } else {
                debug("Método no encontrado: {$methodName}");
                echo "Error: Método no encontrado - {$controllerName}->{$methodName}()";
                echo "<pre>";
                debug_print_backtrace();
                echo "</pre>";
                exit();
            }
        } else {
            debug("Clase no encontrada: {$controllerName}");
            echo "Error: Clase no encontrada - {$controllerName}";
            echo "<pre>";
            debug_print_backtrace();
            echo "</pre>";
            exit();
        }
    } else {
        debug("Archivo no encontrado: {$controllerFile}");
        echo "Error: Controlador no encontrado - {$controllerFile}";
        echo "<pre>";
        debug_print_backtrace();
        echo "</pre>";
        exit();
    }
} catch (Exception $e) {
    debug("Error: " . $e->getMessage());
    echo "Error: " . $e->getMessage();
    echo "<pre>";
    debug_print_backtrace();
    echo "</pre>";
    exit();
}
?> 