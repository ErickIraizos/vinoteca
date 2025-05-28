<?php
// Configuración para entorno de pruebas
define('TEST_MODE', true);
define('TEST_DB_HOST', 'localhost');
define('TEST_DB_NAME', 'licoreria_online');
define('TEST_DB_USER', 'root');
define('TEST_DB_PASS', '');

// Configuración de rutas
define('TEST_BASE_URL', 'http://localhost/vino/');
define('TEST_ASSETS_PATH', __DIR__ . '/../assets/');

// Configuración de logs
define('TEST_LOG_PATH', __DIR__ . '/logs/');
if (!file_exists(TEST_LOG_PATH)) {
    mkdir(TEST_LOG_PATH, 0777, true);
}

// Función para log de pruebas
function test_log($message) {
    $log_file = TEST_LOG_PATH . 'test_' . date('Y-m-d') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$timestamp] $message\n", FILE_APPEND);
}

// Conexión a la base de datos de prueba
try {
    $test_db = new PDO(
        "mysql:host=" . TEST_DB_HOST . ";dbname=" . TEST_DB_NAME,
        TEST_DB_USER,
        TEST_DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    test_log("Conexión a base de datos de prueba establecida");
} catch (PDOException $e) {
    test_log("Error al conectar a la base de datos de prueba: " . $e->getMessage());
    die("Error de conexión a la base de datos de prueba");
} 