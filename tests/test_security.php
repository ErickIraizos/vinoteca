<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../models/Model.php';

class SecurityTest {
    private $db;
    
    public function __construct() {
        global $test_db;
        $this->db = new Model($test_db);
        test_log("Iniciando pruebas de seguridad");
    }
    
    public function testSQLInjection() {
        $testCases = [
            'login' => [
                "' OR '1'='1",
                "'; DROP TABLE usuarios; --",
                "' UNION SELECT * FROM usuarios; --"
            ],
            'busqueda' => [
                "' OR '1'='1",
                "'; DROP TABLE productos; --",
                "' UNION SELECT * FROM productos; --"
            ]
        ];
        
        foreach ($testCases as $type => $cases) {
            foreach ($cases as $test) {
                $query = "SELECT * FROM " . ($type == 'login' ? 'usuarios' : 'productos') . " WHERE " . 
                        ($type == 'login' ? 'email' : 'nombre') . " = :test";
                try {
                    $stmt = $this->db->getPDO()->prepare($query);
                    $stmt->bindValue(':test', $test);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    test_log("Vulnerabilidad SQL encontrada en $type: $test");
                } catch (Exception $e) {
                    test_log("Prueba SQL segura en $type: $test");
                }
            }
        }
    }
    
    public function testXSS() {
        $testCases = [
            'comentario' => [
                "<script>alert('XSS')</script>",
                "<img src='x' onerror='alert(1)'>",
                "javascript:alert(1)"
            ],
            'nombre' => [
                "<script>alert('XSS')</script>",
                "<img src='x' onerror='alert(1)'>",
                "javascript:alert(1)"
            ]
        ];
        
        foreach ($testCases as $type => $cases) {
            foreach ($cases as $test) {
                $sanitized = htmlspecialchars($test, ENT_QUOTES, 'UTF-8');
                if ($sanitized === $test) {
                    test_log("Posible vulnerabilidad XSS en $type: $test");
                } else {
                    test_log("Prueba XSS segura en $type: $test");
                }
            }
        }
    }
    
    public function testSessionSecurity() {
        // Verificar configuración de sesión
        $sessionConfig = [
            'session.cookie_httponly' => true,
            'session.use_only_cookies' => true,
            'session.cookie_secure' => true
        ];
        
        foreach ($sessionConfig as $setting => $expected) {
            $current = ini_get($setting);
            if ($current != $expected) {
                test_log("Configuración de sesión insegura: $setting = $current (debería ser $expected)");
            } else {
                test_log("Configuración de sesión segura: $setting");
            }
        }
    }
} 