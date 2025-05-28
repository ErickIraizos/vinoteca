<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/test_db.php';
require_once __DIR__ . '/test_security.php';

class TestRunner {
    private $results = [];
    
    public function runAllTests() {
        test_log("=== Iniciando suite de pruebas ===");
        
        // Pruebas de base de datos
        $this->runDatabaseTests();
        
        // Pruebas de seguridad
        $this->runSecurityTests();
        
        // Generar reporte
        $this->generateReport();
        
        test_log("=== Suite de pruebas completada ===");
    }
    
    private function runDatabaseTests() {
        test_log("\n=== Ejecutando pruebas de base de datos ===");
        $dbTest = new DatabaseTest();
        
        $this->results['database'] = [
            'connection' => $dbTest->testConnection(),
            'performance' => $dbTest->testQueryPerformance(),
            'transactions' => $dbTest->testTransactions()
        ];
    }
    
    private function runSecurityTests() {
        test_log("\n=== Ejecutando pruebas de seguridad ===");
        $securityTest = new SecurityTest();
        
        $this->results['security'] = [
            'sql_injection' => $securityTest->testSQLInjection(),
            'xss' => $securityTest->testXSS(),
            'session' => $securityTest->testSessionSecurity()
        ];
    }
    
    private function generateReport() {
        $report = "=== Reporte de Pruebas ===\n";
        $report .= "Fecha: " . date('Y-m-d H:i:s') . "\n\n";
        
        foreach ($this->results as $category => $tests) {
            $report .= "=== $category ===\n";
            foreach ($tests as $test => $result) {
                $report .= "$test: " . ($result ? "PASÓ" : "FALLÓ") . "\n";
            }
            $report .= "\n";
        }
        
        $reportFile = TEST_LOG_PATH . 'report_' . date('Y-m-d_H-i-s') . '.txt';
        file_put_contents($reportFile, $report);
        test_log("Reporte generado en: $reportFile");
    }
}

// Ejecutar todas las pruebas
$runner = new TestRunner();
$runner->runAllTests(); 