<?php
require_once 'Model.php';

class CategoriaModel extends Model {
    public function __construct($db) {
        parent::__construct($db);
        $this->table = 'categorias';
    }

    public function getCategorias() {
        try {
            $sql = "SELECT * FROM categorias WHERE activa = 1 ORDER BY nombre";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getCategorias: " . $e->getMessage());
            return [];
        }
    }

    public function getMenuCategorias() {
        try {
            $sql = "SELECT c.*, COUNT(p.producto_id) as total_productos 
                    FROM categorias c 
                    LEFT JOIN productos p ON c.categoria_id = p.categoria_id AND p.activo = 1 
                    WHERE c.activa = 1 
                    GROUP BY c.categoria_id 
                    ORDER BY c.nombre";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getMenuCategorias: " . $e->getMessage());
            return [];
        }
    }

    public function findById($id) {
        try {
            $sql = "SELECT * FROM categorias WHERE categoria_id = ? AND activa = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en findById: " . $e->getMessage());
            return false;
        }
    }

    public function getCategoriasPrincipales() {
        $sql = "SELECT c.*, 
                       (SELECT COUNT(*) FROM productos p WHERE p.categoria_id = c.categoria_id AND p.activo = 1) as total_productos 
                FROM {$this->table} c 
                WHERE c.activa = 1 
                ORDER BY c.nombre ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSubcategorias($categoriaId) {
        $sql = "SELECT c.*, 
                       (SELECT COUNT(*) FROM productos p WHERE p.categoria_id = c.categoria_id AND p.activo = 1) as total_productos 
                FROM {$this->table} c 
                WHERE c.activa = 1 
                ORDER BY c.nombre ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBySlug($slug) {
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug AND activa = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $sql = "SELECT c.*, 
                       (SELECT COUNT(*) FROM productos p WHERE p.categoria_id = c.categoria_id) as total_productos
                FROM {$this->table} c 
                ORDER BY c.nombre ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoriaConProductos($categoriaId, $pagina = 1, $porPagina = 12) {
        // Obtener información de la categoría
        $sql = "SELECT * FROM categorias WHERE categoria_id = :categoria_id AND activa = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':categoria_id', $categoriaId);
        $stmt->execute();
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$categoria) {
            return null;
        }

        // Obtener productos de la categoría
        $offset = ($pagina - 1) * $porPagina;
        $sql = "SELECT p.*, 
                       COALESCE(p.precio_oferta, p.precio) as precio_final,
                       (SELECT AVG(calificacion) FROM resenas WHERE producto_id = p.producto_id) as calificacion_promedio
                FROM productos p 
                WHERE p.categoria_id = :categoria_id 
                AND p.activo = 1 
                ORDER BY p.destacado DESC, p.fecha_creacion DESC 
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':categoria_id', $categoriaId);
        $stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Contar total de productos
        $sql = "SELECT COUNT(*) as total 
                FROM productos 
                WHERE categoria_id = :categoria_id AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':categoria_id', $categoriaId);
        $stmt->execute();
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        return [
            'categoria' => $categoria,
            'productos' => $productos,
            'total' => $total,
            'paginas' => ceil($total / $porPagina),
            'pagina_actual' => $pagina
        ];
    }

    public function getEstadisticas() {
        $sql = "SELECT c.*,
                       COUNT(p.producto_id) as total_productos,
                       COALESCE(MIN(p.precio), 0) as precio_min,
                       COALESCE(MAX(p.precio), 0) as precio_max,
                       COALESCE(AVG(p.precio), 0) as precio_promedio
                FROM categorias c
                LEFT JOIN productos p ON c.categoria_id = p.categoria_id AND p.activo = 1
                WHERE c.activa = 1
                GROUP BY c.categoria_id
                ORDER BY total_productos DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarTotal() {
        try {
            $sql = "SELECT COUNT(*) FROM categorias WHERE activa = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error en contarTotal: " . $e->getMessage());
            return 0;
        }
    }

    public function create($data) {
        try {
            $sql = "INSERT INTO categorias (nombre, descripcion, imagen_url, activa) 
                    VALUES (:nombre, :descripcion, :imagen_url, :activa)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':nombre', $data['nombre']);
            $stmt->bindValue(':descripcion', $data['descripcion'] ?? '');
            $stmt->bindValue(':imagen_url', $data['imagen_url'] ?? null);
            $stmt->bindValue(':activa', isset($data['activa']) ? 1 : 0, PDO::PARAM_INT);
            
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error en create: " . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, $data) {
        try {
            $sql = "UPDATE categorias 
                    SET nombre = :nombre, 
                        descripcion = :descripcion";
            
            $params = [
                ':categoria_id' => $id,
                ':nombre' => $data['nombre'],
                ':descripcion' => $data['descripcion'] ?? ''
            ];

            if (isset($data['imagen_url'])) {
                $sql .= ", imagen_url = :imagen_url";
                $params[':imagen_url'] = $data['imagen_url'];
            }

            if (isset($data['activa'])) {
                $sql .= ", activa = :activa";
                $params[':activa'] = $data['activa'] ? 1 : 0;
            }

            $sql .= " WHERE categoria_id = :categoria_id";
            
            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en update: " . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id) {
        try {
            // Primero verificamos si hay productos asociados
            $sql = "SELECT COUNT(*) FROM productos WHERE categoria_id = :categoria_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':categoria_id', $id);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                // Si hay productos, solo desactivamos la categoría
                $sql = "UPDATE categorias SET activa = 0 WHERE categoria_id = :categoria_id";
            } else {
                // Si no hay productos, eliminamos la categoría
                $sql = "DELETE FROM categorias WHERE categoria_id = :categoria_id";
            }

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':categoria_id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en delete: " . $e->getMessage());
            throw $e;
        }
    }
}
?> 