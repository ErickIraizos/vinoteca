<?php
require_once 'Model.php';

class ReporteModel extends Model {
    public function __construct($db) {
        parent::__construct($db);
    }

    public function getDatos($tipo, $desde, $hasta) {
        try {
            switch ($tipo) {
                case 'ventas':
                    return $this->getReporteVentas($desde, $hasta);
                case 'productos':
                    return $this->getReporteProductos($desde, $hasta);
                case 'usuarios':
                    return $this->getReporteUsuarios($desde, $hasta);
                default:
                    return [];
            }
        } catch (PDOException $e) {
            error_log("Error en getDatos: " . $e->getMessage());
            return [];
        }
    }

    private function getReporteVentas($desde, $hasta) {
        $sql = "SELECT 
                    DATE(fecha_pedido) as fecha,
                    COUNT(*) as total_pedidos,
                    SUM(total) as total_ventas
                FROM pedidos
                WHERE fecha_pedido BETWEEN :desde AND :hasta
                GROUP BY DATE(fecha_pedido)
                ORDER BY fecha_pedido DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'desde' => $desde,
            'hasta' => $hasta . ' 23:59:59'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getReporteProductos($desde, $hasta) {
        $sql = "SELECT 
                    p.nombre,
                    COUNT(dp.producto_id) as total_vendidos,
                    SUM(dp.cantidad) as cantidad_total,
                    SUM(dp.precio * dp.cantidad) as total_ventas
                FROM detalles_pedido dp
                JOIN productos p ON p.producto_id = dp.producto_id
                JOIN pedidos pe ON pe.pedido_id = dp.pedido_id
                WHERE pe.fecha_pedido BETWEEN :desde AND :hasta
                GROUP BY dp.producto_id
                ORDER BY total_vendidos DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'desde' => $desde,
            'hasta' => $hasta . ' 23:59:59'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getReporteUsuarios($desde, $hasta) {
        $sql = "SELECT 
                    u.nombre,
                    u.email,
                    COUNT(p.pedido_id) as total_pedidos,
                    SUM(p.total) as total_compras
                FROM usuarios u
                LEFT JOIN pedidos p ON p.usuario_id = u.usuario_id
                    AND p.fecha_pedido BETWEEN :desde AND :hasta
                GROUP BY u.usuario_id
                ORDER BY total_compras DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'desde' => $desde,
            'hasta' => $hasta . ' 23:59:59'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVentasTotales($desde, $hasta) {
        $sql = "SELECT COALESCE(SUM(total), 0) as total 
                FROM pedidos 
                WHERE fecha_pedido BETWEEN :desde AND :hasta 
                AND estado = 'entregado'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':desde', $desde);
        $stmt->bindValue(':hasta', $hasta);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }

    public function getPedidosCompletados($desde = null, $hasta = null) {
        $sql = "SELECT COUNT(*) as total FROM pedidos WHERE estado = 'entregado'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getProductosVendidos($desde, $hasta) {
        $sql = "SELECT COALESCE(SUM(dp.cantidad), 0) as total 
                FROM detalles_pedido dp 
                JOIN pedidos p ON p.pedido_id = dp.pedido_id 
                WHERE p.fecha_pedido BETWEEN :desde AND :hasta 
                AND p.estado = 'entregado'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':desde', $desde);
        $stmt->bindValue(':hasta', $hasta);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }

    public function getNuevosClientes($desde = null, $hasta = null) {
        $sql = "SELECT COUNT(*) as total FROM usuarios";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getVentasPorMes($desde, $hasta) {
        $sql = "SELECT DATE_FORMAT(fecha_pedido, '%Y-%m') as mes, SUM(total) as total FROM pedidos WHERE fecha_pedido BETWEEN :desde AND :hasta AND estado != 'cancelado' GROUP BY mes ORDER BY mes";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'desde' => $desde,
            'hasta' => $hasta . ' 23:59:59'
        ]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $totales = [];
        foreach ($result as $row) {
            $totales[$row['mes']] = (float)$row['total'];
        }
        return array_values($totales);
    }

    public function getMeses($desde, $hasta) {
        $sql = "SELECT DISTINCT DATE_FORMAT(fecha_pedido, '%Y-%m') as mes FROM pedidos WHERE fecha_pedido BETWEEN :desde AND :hasta ORDER BY mes";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'desde' => $desde,
            'hasta' => $hasta . ' 23:59:59'
        ]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_column($result, 'mes');
    }

    public function getProductosPopulares($desde, $hasta) {
        $sql = "SELECT p.nombre, SUM(dp.cantidad) as cantidad FROM detalles_pedido dp JOIN productos p ON p.producto_id = dp.producto_id JOIN pedidos pe ON pe.pedido_id = dp.pedido_id WHERE pe.fecha_pedido BETWEEN :desde AND :hasta AND pe.estado != 'cancelado' GROUP BY dp.producto_id ORDER BY cantidad DESC LIMIT 5";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'desde' => $desde,
            'hasta' => $hasta . ' 23:59:59'
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVentasPorCategoria($desde, $hasta) {
        $sql = "SELECT c.nombre as categoria, SUM(dp.subtotal) as total_ventas
                FROM detalles_pedido dp
                JOIN productos p ON p.producto_id = dp.producto_id
                JOIN categorias c ON c.categoria_id = p.categoria_id
                JOIN pedidos pe ON pe.pedido_id = dp.pedido_id
                WHERE pe.fecha_pedido BETWEEN :desde AND :hasta
                  AND pe.estado != 'cancelado'
                GROUP BY c.categoria_id
                ORDER BY total_ventas DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'desde' => $desde,
            'hasta' => $hasta . ' 23:59:59'
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 