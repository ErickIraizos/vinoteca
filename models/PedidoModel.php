<?php
class PedidoModel extends Model {
    public function __construct($db) {
        parent::__construct($db);
        $this->table = 'pedidos';
    }

    public function getAll($pagina = 1, $estado = '') {
        $limit = 10;
        $offset = ($pagina - 1) * $limit;
        
        $sql = "SELECT p.*, u.nombre as nombre_cliente, u.email as email_cliente 
                FROM pedidos p 
                LEFT JOIN usuarios u ON p.usuario_id = u.usuario_id 
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($estado)) {
            $sql .= " AND p.estado = :estado";
            $params[':estado'] = $estado;
        }
        
        $sql .= " ORDER BY p.fecha_pedido DESC LIMIT :limit OFFSET :offset";
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        }
        $stmt->execute();
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Contar total de registros para paginación
        $sqlTotal = "SELECT COUNT(*) as total FROM pedidos WHERE 1=1" . 
                   (!empty($estado) ? " AND estado = :estado" : "");
        $stmtTotal = $this->db->prepare($sqlTotal);
        if (!empty($estado)) {
            $stmtTotal->bindValue(':estado', $estado);
        }
        $stmtTotal->execute();
        $total = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];
        
        return [
            'items' => $pedidos,
            'total' => $total,
            'paginas' => ceil($total / $limit)
        ];
    }

    public function getDetalle($id) {
        $sql = "SELECT p.*, u.nombre as nombre_cliente, u.email as email_cliente,
                       u.telefono as telefono_cliente, u.direccion as direccion_cliente
                FROM pedidos p 
                LEFT JOIN usuarios u ON p.usuario_id = u.usuario_id 
                WHERE p.pedido_id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!empty($pedido)) {
            // Obtener detalles del pedido
            $sql = "SELECT dp.*, pr.nombre as producto_nombre, pr.imagen_url 
                    FROM detalles_pedido dp
                    LEFT JOIN productos pr ON dp.producto_id = pr.producto_id
                    WHERE dp.pedido_id = :pedido_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':pedido_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $pedido['detalles'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $pedido;
        }
        
        return null;
    }

    public function actualizarEstado($id, $estado) {
        $sql = "UPDATE pedidos SET estado = :estado WHERE pedido_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function contarTotal() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM pedidos");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function obtenerVentasRecientes($limite = 5) {
        $sql = "SELECT p.*, u.nombre as nombre_cliente 
                FROM pedidos p 
                LEFT JOIN usuarios u ON p.usuario_id = u.usuario_id 
                ORDER BY p.fecha_pedido DESC 
                LIMIT :limite";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function calcularIngresosTotales() {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(total), 0) as total FROM pedidos WHERE estado != 'cancelado'"
        );
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function getPedidosPaginados($pagina = 1, $por_pagina = 10, $usuario_id) {
        try {
            $inicio = ($pagina - 1) * $por_pagina;
            
            // Consulta para obtener los pedidos del usuario específico
            $sql = "SELECT * FROM pedidos WHERE usuario_id = :usuario_id ORDER BY fecha_pedido DESC LIMIT :inicio, :por_pagina";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
            $stmt->bindParam(':por_pagina', $por_pagina, PDO::PARAM_INT);
            $stmt->execute();
            $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Consulta para obtener el total de registros del usuario
            $sql = "SELECT COUNT(*) FROM pedidos WHERE usuario_id = :usuario_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->execute();
            $total = $stmt->fetchColumn();

            return [
                'items' => $pedidos,
                'total' => $total,
                'paginas' => ceil($total / $por_pagina)
            ];
        } catch (PDOException $e) {
            error_log("Error en getPedidosPaginados: " . $e->getMessage());
            return [
                'items' => [],
                'total' => 0,
                'paginas' => 1
            ];
        }
    }

    public function getTotalPedidosUsuario($usuario_id) {
        try {
            $sql = "SELECT COUNT(*) FROM pedidos WHERE usuario_id = :usuario_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error en getTotalPedidosUsuario: " . $e->getMessage());
            return 0;
        }
    }

    public function getTotalPedidosPorEstadoUsuario($usuario_id, $estado) {
        try {
            $sql = "SELECT COUNT(*) FROM pedidos WHERE usuario_id = :usuario_id AND estado = :estado";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error en getTotalPedidosPorEstadoUsuario: " . $e->getMessage());
            return 0;
        }
    }

    public function getTotalVentasUsuario($usuario_id) {
        try {
            $sql = "SELECT COALESCE(SUM(total), 0) FROM pedidos WHERE usuario_id = :usuario_id AND estado != 'cancelado'";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error en getTotalVentasUsuario: " . $e->getMessage());
            return 0;
        }
    }

    public function cancelarPedido($pedido_id) {
        try {
            $sql = "UPDATE pedidos SET estado = 'cancelado' WHERE pedido_id = :pedido_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pedido_id', $pedido_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en cancelarPedido: " . $e->getMessage());
            return false;
        }
    }

    public function getDetallesPedido($pedido_id) {
        try {
            // Consulta para obtener los detalles del pedido con el nombre del producto
            $sql = "SELECT 
                    dp.detalle_id,
                    dp.pedido_id,
                    dp.producto_id,
                    dp.cantidad,
                    dp.precio_unitario,
                    dp.subtotal,
                    p.nombre as nombre_producto
                FROM detalles_pedido dp
                INNER JOIN productos p ON dp.producto_id = p.producto_id
                WHERE dp.pedido_id = :pedido_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pedido_id', $pedido_id, PDO::PARAM_INT);
            
            if (!$stmt->execute()) {
                error_log("Error al ejecutar la consulta de detalles del pedido: " . implode(", ", $stmt->errorInfo()));
                return [];
            }
            
            $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($detalles)) {
                error_log("No se encontraron detalles para el pedido ID: " . $pedido_id);
            } else {
                error_log("Detalles encontrados para el pedido ID " . $pedido_id . ": " . print_r($detalles, true));
            }
            
            return $detalles;
        } catch (PDOException $e) {
            error_log("Error en getDetallesPedido: " . $e->getMessage());
            return [];
        }
    }

    public function eliminarPorUsuario($usuario_id) {
        try {
            $sql = "DELETE FROM pedidos WHERE usuario_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$usuario_id]);
        } catch (PDOException $e) {
            error_log("Error en eliminarPorUsuario (pedidos): " . $e->getMessage());
            return false;
        }
    }

    public function getTotalProductosPedido($pedido_id) {
        $sql = "SELECT COALESCE(SUM(cantidad), 0) as total 
                FROM detalles_pedido 
                WHERE pedido_id = :pedido_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':pedido_id', $pedido_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function crear($data) {
        $sql = "INSERT INTO pedidos (usuario_id, direccion_envio, estado, metodo_pago, fecha_pedido, total)
                VALUES (:usuario_id, :direccion_envio, :estado, :metodo_pago, NOW(), 0)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':usuario_id', $data['usuario_id'], PDO::PARAM_INT);
        $stmt->bindParam(':direccion_envio', $data['direccion_envio']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':metodo_pago', $data['metodo_pago']);
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function actualizarTotal($pedido_id) {
        $sql = "SELECT SUM(subtotal) as total FROM detalles_pedido WHERE pedido_id = :pedido_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':pedido_id', $pedido_id, PDO::PARAM_INT);
        $stmt->execute();
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        $sql = "UPDATE pedidos SET total = :total WHERE pedido_id = :pedido_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':pedido_id', $pedido_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function agregarItem($pedido_id, $item) {
        require_once 'models/ProductoModel.php';
        $productoModel = new ProductoModel($this->db);
        $precio = $productoModel->getPrecioConPromocion($item['producto_id']);
        $sql = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio_unitario, subtotal)
                VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario, :subtotal)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':pedido_id', $pedido_id, PDO::PARAM_INT);
        $stmt->bindParam(':producto_id', $item['producto_id'], PDO::PARAM_INT);
        $stmt->bindParam(':cantidad', $item['cantidad'], PDO::PARAM_INT);
        $stmt->bindParam(':precio_unitario', $precio);
        $subtotal = $precio * $item['cantidad'];
        $stmt->bindParam(':subtotal', $subtotal);
        $result = $stmt->execute();
        $this->actualizarTotal($pedido_id);
        return $result;
    }

    // Verifica si un usuario ha comprado un producto específico (estado entregado)
    public function haCompradoProducto($usuario_id, $producto_id) {
        $sql = "SELECT COUNT(*) as total
                FROM pedidos p
                JOIN detalles_pedido dp ON p.pedido_id = dp.pedido_id
                WHERE p.usuario_id = :usuario_id
                AND dp.producto_id = :producto_id
                AND p.estado = 'entregado'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuario_id);
        $stmt->bindValue(':producto_id', $producto_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['total'] > 0;
    }

    public function recalcularTotalesPedidos() {
        $sql = "SELECT pedido_id FROM pedidos";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($pedidos as $pedido) {
            $this->actualizarTotal($pedido['pedido_id']);
        }
    }
} 