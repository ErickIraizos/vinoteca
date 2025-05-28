<?php
require_once 'Model.php';

class FavoritoModel extends Model {
    public function __construct($db) {
        parent::__construct($db);
        $this->table = 'favoritos';
    }

    public function getFavoritosUsuario($usuario_id, $pagina = 1, $por_pagina = 12) {
        try {
            $offset = ($pagina - 1) * $por_pagina;

            // Obtener total de favoritos
            $sql = "SELECT COUNT(*) as total FROM favoritos WHERE usuario_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id]);
            $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Obtener favoritos con detalles del producto
            $sql = "SELECT p.*, c.nombre as categoria_nombre, f.fecha_agregado as fecha_agregado
                    FROM favoritos f 
                    INNER JOIN productos p ON f.producto_id = p.producto_id 
                    LEFT JOIN categorias c ON p.categoria_id = c.categoria_id 
                    WHERE f.usuario_id = ? AND p.activo = 1
                    ORDER BY f.fecha_agregado DESC 
                    LIMIT ? OFFSET ?";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $usuario_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $por_pagina, PDO::PARAM_INT);
            $stmt->bindValue(3, $offset, PDO::PARAM_INT);
            $stmt->execute();
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Añadir precio promocional y descuento
            require_once 'models/ProductoModel.php';
            $productoModel = new ProductoModel($this->db);
            foreach ($items as &$item) {
                $item['precio_promocion'] = $productoModel->getPrecioConPromocion($item['producto_id']);
                // Obtener el porcentaje de descuento si hay promoción activa
                $hoy = date('Y-m-d');
                $sqlPromo = "SELECT pr.descuento_porcentaje
                             FROM promociones_productos pp
                             INNER JOIN promociones pr ON pp.promocion_id = pr.promocion_id
                             WHERE pp.producto_id = ?
                               AND pr.activo = 1
                               AND pr.fecha_inicio <= ?
                               AND pr.fecha_fin >= ?
                             ORDER BY pr.descuento_porcentaje DESC
                             LIMIT 1";
                $stmtPromo = $this->db->prepare($sqlPromo);
                $stmtPromo->execute([$item['producto_id'], $hoy, $hoy]);
                $promo = $stmtPromo->fetch(PDO::FETCH_ASSOC);
                $item['descuento_porcentaje'] = $promo ? $promo['descuento_porcentaje'] : 0;
            }

            return [
                'items' => $items,
                'total' => $total,
                'paginas' => ceil($total / $por_pagina)
            ];
        } catch (PDOException $e) {
            error_log("Error en getFavoritosUsuario: " . $e->getMessage());
            return [
                'items' => [],
                'total' => 0,
                'paginas' => 0
            ];
        }
    }

    public function eliminar($usuario_id, $producto_id) {
        try {
            $sql = "DELETE FROM favoritos WHERE usuario_id = ? AND producto_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$usuario_id, $producto_id]);
        } catch (PDOException $e) {
            error_log("Error en eliminar favorito: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarTodosPorUsuario($usuario_id) {
        try {
            $sql = "DELETE FROM favoritos WHERE usuario_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$usuario_id]);
        } catch (PDOException $e) {
            error_log("Error en eliminarTodosPorUsuario (favoritos): " . $e->getMessage());
            return false;
        }
    }
} 