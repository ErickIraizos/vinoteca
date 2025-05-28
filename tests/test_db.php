<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../models/Model.php';

class DatabaseTest {
    private $db;
    
    public function __construct() {
        global $test_db;
        $this->db = new Model($test_db);
        test_log("Iniciando pruebas de base de datos");
    }
    
    public function testQueryPerformance() {
        $queries = [
            'productos' => "SELECT p.*, c.nombre as categoria 
                          FROM productos p 
                          JOIN categorias c ON p.categoria_id = c.categoria_id 
                          WHERE p.stock > 0 
                          ORDER BY p.precio DESC 
                          LIMIT 100",
            'pedidos' => "SELECT p.*, u.nombre as usuario 
                         FROM pedidos p 
                         JOIN usuarios u ON p.usuario_id = u.usuario_id 
                         ORDER BY p.fecha_pedido DESC 
                         LIMIT 50",
            'resenas' => "SELECT r.*, p.nombre as producto 
                         FROM resenas r 
                         JOIN productos p ON r.producto_id = p.producto_id 
                         WHERE r.aprobada = 1 
                         ORDER BY r.fecha_creacion DESC 
                         LIMIT 20"
        ];
        
        foreach ($queries as $name => $query) {
            $start = microtime(true);
            $stmt = $this->db->getPDO()->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $end = microtime(true);
            $time = $end - $start;
            
            test_log("Query $name: " . count($result) . " registros en $time segundos");
        }
    }
    
    public function testConnection() {
        try {
            $stmt = $this->db->getPDO()->query("SELECT 1");
            test_log("Conexión a base de datos exitosa");
            return true;
        } catch (Exception $e) {
            test_log("Error de conexión: " . $e->getMessage());
            return false;
        }
    }
    
    public function testTransactions() {
        try {
            $this->db->getPDO()->beginTransaction();
            
            // Insertar producto de prueba
            $producto = [
                'nombre' => 'Vino Test',
                'precio' => 99.99,
                'stock' => 10,
                'categoria_id' => 1,
                'descripcion' => 'Vino de prueba',
                'marca' => 'Marca Test',
                'grado_alcoholico' => 13.5,
                'pais_origen' => 'España'
            ];
            
            $this->db->setTable('productos');
            $this->db->create($producto);
            $this->db->getPDO()->commit();
            
            test_log("Transacción completada exitosamente");
            return true;
        } catch (Exception $e) {
            $this->db->getPDO()->rollback();
            test_log("Error en transacción: " . $e->getMessage());
            return false;
        }
    }
} 