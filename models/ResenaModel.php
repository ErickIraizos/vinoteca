<?php
class ResenaModel extends Model {
    protected $table = 'resenas';

    public function getResenasDestacadas($limit = 3) {
        $sql = "SELECT r.*, 
                       p.nombre as producto_nombre, 
                       p.imagen_url as producto_imagen,
                       u.nombre as usuario_nombre
                FROM resenas r
                JOIN productos p ON r.producto_id = p.producto_id
                JOIN usuarios u ON r.usuario_id = u.usuario_id
                WHERE r.aprobada = 1
                ORDER BY r.fecha_creacion DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getResenasPorProducto($productoId, $pagina = 1, $porPagina = 5) {
        $offset = ($pagina - 1) * $porPagina;
        
        $sql = "SELECT r.*, 
                       u.nombre as usuario_nombre,
                       u.fecha_registro as usuario_fecha_registro
                FROM resenas r
                JOIN usuarios u ON r.usuario_id = u.usuario_id
                WHERE r.producto_id = :producto_id
                AND r.aprobada = 1
                ORDER BY r.fecha_creacion DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':producto_id', $productoId);
        $stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $resenas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener total de reseñas
        $sql = "SELECT COUNT(*) as total 
                FROM resenas 
                WHERE producto_id = :producto_id 
                AND aprobada = 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':producto_id', $productoId);
        $stmt->execute();
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        return [
            'resenas' => $resenas,
            'total' => $total,
            'paginas' => ceil($total / $porPagina),
            'pagina_actual' => $pagina
        ];
    }

    public function getEstadisticasResenas($productoId) {
        $sql = "SELECT 
                    COUNT(*) as total_resenas,
                    AVG(calificacion) as promedio_calificacion,
                    COUNT(CASE WHEN calificacion = 5 THEN 1 END) as cinco_estrellas,
                    COUNT(CASE WHEN calificacion = 4 THEN 1 END) as cuatro_estrellas,
                    COUNT(CASE WHEN calificacion = 3 THEN 1 END) as tres_estrellas,
                    COUNT(CASE WHEN calificacion = 2 THEN 1 END) as dos_estrellas,
                    COUNT(CASE WHEN calificacion = 1 THEN 1 END) as una_estrella
                FROM resenas
                WHERE producto_id = :producto_id
                AND aprobada = 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':producto_id', $productoId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function puedeResenar($usuarioId, $productoId) {
        // Verificar si el usuario ha comprado el producto
        $sql = "SELECT COUNT(*) as total
                FROM pedidos p
                JOIN detalles_pedido dp ON p.pedido_id = dp.pedido_id
                WHERE p.usuario_id = :usuario_id
                AND dp.producto_id = :producto_id
                AND p.estado = 'entregado'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuarioId);
        $stmt->bindValue(':producto_id', $productoId);
        $stmt->execute();
        $haComprado = $stmt->fetch(PDO::FETCH_ASSOC)['total'] > 0;

        // Permitir varias reseñas por usuario-producto
        return $haComprado;
    }

    public function crearResena($data) {
        if (!$this->puedeResenar($data['usuario_id'], $data['producto_id'])) {
            return false;
        }

        return $this->create([
            'producto_id' => $data['producto_id'],
            'usuario_id' => $data['usuario_id'],
            'calificacion' => $data['calificacion'],
            'comentario' => $data['comentario'],
            'aprobada' => 1 // Se aprueba automáticamente
        ]);
    }
}
?> 