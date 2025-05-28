<?php
require_once 'Model.php';

class ProductoModel extends Model {
    public function __construct($db) {
        parent::__construct($db);
        $this->table = 'productos';
    }

    public function getProductos($categoria_id = null, $busqueda = null, $orden = 'nombre', $pagina = 1, $por_pagina = 12) {
        try {
            $params = [];
            $where = ['activo = 1'];
            
            if ($categoria_id) {
                $where[] = 'categoria_id = ?';
                $params[] = $categoria_id;
            }
            
            if ($busqueda) {
                $where[] = '(nombre LIKE ? OR descripcion LIKE ? OR marca LIKE ?)';
                $busqueda = "%$busqueda%";
                $params = array_merge($params, [$busqueda, $busqueda, $busqueda]);
            }

            $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

            // Determinar el orden
            $orderBy = match($orden) {
                'nombre_desc' => 'nombre DESC',
                'precio' => 'precio ASC',
                'precio_desc' => 'precio DESC',
                'nuevo' => 'fecha_creacion DESC',
                default => 'nombre ASC'
            };

            // Contar total de registros
            $sql = "SELECT COUNT(*) FROM productos $whereClause";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $total = $stmt->fetchColumn();

            // Calcular offset y límite
            $offset = ($pagina - 1) * $por_pagina;
            
            // Obtener productos
            $sql = "SELECT * FROM productos 
                   $whereClause 
                   ORDER BY $orderBy 
                   LIMIT $por_pagina OFFSET $offset";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Calcular precio promocional para cada producto
            foreach ($items as &$item) {
                $item['precio_promocion'] = $this->getPrecioConPromocion($item['producto_id']);
            }

            return [
                'items' => $items,
                'total' => $total,
                'total_paginas' => ceil($total / $por_pagina)
            ];
        } catch (PDOException $e) {
            error_log("Error en getProductos: " . $e->getMessage());
            return [
                'items' => [],
                'total' => 0,
                'total_paginas' => 0
            ];
        }
    }

    public function getDetalle($id) {
        try {
            $sql = "SELECT p.*, c.nombre as categoria_nombre 
                    FROM productos p 
                    LEFT JOIN categorias c ON p.categoria_id = c.categoria_id 
                    WHERE p.producto_id = ? AND p.activo = 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($producto) {
                $producto['precio_promocion'] = $this->getPrecioConPromocion($producto['producto_id']);
            }
            return $producto;
        } catch (PDOException $e) {
            error_log("Error en getDetalle: " . $e->getMessage());
            return false;
        }
    }

    public function getRelacionados($categoria_id, $producto_id, $limite = 4) {
        try {
            $sql = "SELECT * FROM productos 
                    WHERE categoria_id = ? 
                    AND producto_id != ? 
                    AND activo = 1 
                    ORDER BY RAND() 
                    LIMIT ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$categoria_id, $producto_id, $limite]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getRelacionados: " . $e->getMessage());
            return [];
        }
    }

    public function getDestacados($pagina = 1, $porPagina = 6) {
        $offset = ($pagina - 1) * $porPagina;
        
        // Obtener total de productos destacados
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE destacado = 1 AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Calcular total de páginas
        $paginas = ceil($total / $porPagina);
        
        // Obtener productos destacados
        $sql = "SELECT p.*, c.nombre as categoria_nombre 
                FROM {$this->table} p 
                LEFT JOIN categorias c ON p.categoria_id = c.categoria_id 
                WHERE p.destacado = 1 AND p.activo = 1 
                ORDER BY p.fecha_creacion DESC 
                LIMIT :offset, :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
        $stmt->execute();
        
        return [
            'productos' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total' => $total,
            'paginas' => $paginas
        ];
    }

    public function getByCategoria($categoriaId, $pagina = 1, $porPagina = 6) {
        $offset = ($pagina - 1) * $porPagina;
        
        // Obtener total de productos en la categoría
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE categoria_id = :categoria_id AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':categoria_id', $categoriaId);
        $stmt->execute();
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Calcular total de páginas
        $paginas = ceil($total / $porPagina);
        
        // Obtener productos
        $sql = "SELECT p.*, c.nombre as categoria_nombre 
                FROM {$this->table} p 
                LEFT JOIN categorias c ON p.categoria_id = c.categoria_id 
                WHERE p.categoria_id = :categoria_id AND p.activo = 1 
                ORDER BY p.fecha_creacion DESC 
                LIMIT :offset, :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':categoria_id', $categoriaId);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
        $stmt->execute();
        
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Calcular precio promocional y descuento para cada producto
        foreach ($productos as &$item) {
            $item['precio_promocion'] = $this->getPrecioConPromocion($item['producto_id']);
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
            'productos' => $productos,
            'total' => $total,
            'paginas' => $paginas
        ];
    }

    public function getOfertas($porPagina = 12, $pagina = 1, $categoria_id = null) {
        $hoy = date('Y-m-d');
        $offset = ($pagina - 1) * $porPagina;
        $params = [$hoy, $hoy];
        $categoriaSql = '';
        if ($categoria_id) {
            $categoriaSql = ' AND p.categoria_id = ?';
            $params[] = $categoria_id;
        }
        $sql = "SELECT p.*, c.nombre as categoria_nombre, pr.descuento_porcentaje,
                       (p.precio * (1 - pr.descuento_porcentaje / 100)) as precio_promocion
                FROM productos p
                INNER JOIN promociones_productos pp ON p.producto_id = pp.producto_id
                INNER JOIN promociones pr ON pp.promocion_id = pr.promocion_id
                JOIN categorias c ON p.categoria_id = c.categoria_id
                WHERE pr.activo = 1
                  AND pr.fecha_inicio <= ?
                  AND pr.fecha_fin >= ?
                  AND p.activo = 1
                  $categoriaSql
                ORDER BY pr.descuento_porcentaje DESC, p.nombre ASC
                LIMIT ? OFFSET ?";
        $params[] = (int)$porPagina;
        $params[] = (int)$offset;
        $stmt = $this->db->prepare($sql);
        foreach ($params as $i => $val) {
            $type = is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($i + 1, $val, $type);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($termino, $filtros = [], $pagina = 1, $porPagina = 12) {
        $offset = ($pagina - 1) * $porPagina;
        $whereClauses = ["p.activo = 1"];
        $params = [];

        // Búsqueda por término
        if ($termino) {
            $whereClauses[] = "(p.nombre LIKE :termino OR p.descripcion LIKE :termino OR p.marca LIKE :termino)";
            $params[':termino'] = "%$termino%";
        }

        // Filtros
        if (!empty($filtros['categoria_id'])) {
            $whereClauses[] = "p.categoria_id = :categoria_id";
            $params[':categoria_id'] = $filtros['categoria_id'];
        }

        if (!empty($filtros['precio_min'])) {
            $whereClauses[] = "p.precio >= :precio_min";
            $params[':precio_min'] = $filtros['precio_min'];
        }

        if (!empty($filtros['precio_max'])) {
            $whereClauses[] = "p.precio <= :precio_max";
            $params[':precio_max'] = $filtros['precio_max'];
        }

        if (!empty($filtros['grado_min'])) {
            $whereClauses[] = "p.grado_alcoholico >= :grado_min";
            $params[':grado_min'] = $filtros['grado_min'];
        }

        if (!empty($filtros['grado_max'])) {
            $whereClauses[] = "p.grado_alcoholico <= :grado_max";
            $params[':grado_max'] = $filtros['grado_max'];
        }

        if (!empty($filtros['pais'])) {
            $whereClauses[] = "p.pais_origen = :pais";
            $params[':pais'] = $filtros['pais'];
        }

        $whereClause = implode(' AND ', $whereClauses);
        
        // Ordenamiento
        $orderBy = "p.fecha_creacion DESC"; // por defecto
        if (!empty($filtros['ordenar_por'])) {
            switch ($filtros['ordenar_por']) {
                case 'precio_asc':
                    $orderBy = "p.precio ASC";
                    break;
                case 'precio_desc':
                    $orderBy = "p.precio DESC";
                    break;
                case 'nombre_asc':
                    $orderBy = "p.nombre ASC";
                    break;
                case 'popularidad':
                    $orderBy = "(SELECT COUNT(*) FROM detalles_pedido dp WHERE dp.producto_id = p.producto_id) DESC";
                    break;
            }
        }

        // Query principal
        $sql = "SELECT p.*, c.nombre as categoria_nombre,
                       COALESCE(p.precio_oferta, p.precio) as precio_final
                FROM productos p 
                JOIN categorias c ON p.categoria_id = c.categoria_id 
                WHERE $whereClause 
                ORDER BY $orderBy 
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Contar total de resultados
        $sqlCount = "SELECT COUNT(*) as total 
                     FROM productos p 
                     WHERE $whereClause";
        
        $stmtCount = $this->db->prepare($sqlCount);
        foreach ($params as $key => $value) {
            $stmtCount->bindValue($key, $value);
        }
        $stmtCount->execute();
        $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

        return [
            'productos' => $productos,
            'total' => $total,
            'paginas' => ceil($total / $porPagina),
            'pagina_actual' => $pagina
        ];
    }

    public function getDetalles($productoId) {
        $sql = "SELECT p.*, c.nombre as categoria_nombre,
                       (SELECT AVG(calificacion) FROM resenas WHERE producto_id = p.producto_id) as calificacion_promedio,
                       (SELECT COUNT(*) FROM resenas WHERE producto_id = p.producto_id) as total_resenas
                FROM productos p 
                JOIN categorias c ON p.categoria_id = c.categoria_id 
                WHERE p.producto_id = :producto_id AND p.activo = 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':producto_id', $productoId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProductosPopulares($limit = 8) {
        $sql = "SELECT 
                    p.producto_id,
                    p.nombre,
                    COALESCE(SUM(dp.cantidad), 0) AS ventas,
                    COALESCE(SUM(dp.subtotal), 0) AS total
                FROM productos p
                LEFT JOIN detalles_pedido dp ON p.producto_id = dp.producto_id
                LEFT JOIN pedidos pe ON dp.pedido_id = pe.pedido_id
                WHERE p.activo = 1 AND (pe.estado IS NULL OR pe.estado != 'cancelado')
                GROUP BY p.producto_id, p.nombre
                ORDER BY ventas DESC, p.fecha_creacion DESC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNovedades($pagina = 1, $porPagina = 12) {
        try {
            $offset = ($pagina - 1) * $porPagina;
            
            // Obtener total de productos
            $sqlCount = "SELECT COUNT(*) as total 
                        FROM productos p 
                        WHERE p.activo = 1";
            $stmtCount = $this->db->prepare($sqlCount);
            $stmtCount->execute();
            $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Obtener productos
            $sql = "SELECT p.*, c.nombre as categoria_nombre,
                           COALESCE(p.precio_oferta, p.precio) as precio_final
                    FROM productos p 
                    JOIN categorias c ON p.categoria_id = c.categoria_id 
                    WHERE p.activo = 1 
                    ORDER BY p.fecha_creacion DESC 
                    LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Calcular precio promocional y descuento para cada producto
            foreach ($productos as &$item) {
                $item['precio_promocion'] = $this->getPrecioConPromocion($item['producto_id']);
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
                'productos' => $productos,
                'total' => $total,
                'paginas' => ceil($total / $porPagina),
                'pagina_actual' => $pagina
            ];
        } catch (PDOException $e) {
            error_log("Error en getNovedades: " . $e->getMessage());
            return [
                'productos' => [],
                'total' => 0,
                'paginas' => 0,
                'pagina_actual' => $pagina
            ];
        }
    }

    public function toggleFavorito($usuario_id, $producto_id) {
        try {
            $this->db->beginTransaction();

            // Verificar si ya existe el favorito
            $sql = "SELECT favorito_id FROM favoritos WHERE usuario_id = ? AND producto_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id, $producto_id]);
            $favorito = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($favorito) {
                // Si existe, eliminarlo
                $sql = "DELETE FROM favoritos WHERE favorito_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$favorito['favorito_id']]);
                $esFavorito = false;
            } else {
                // Si no existe, agregarlo
                $sql = "INSERT INTO favoritos (usuario_id, producto_id) VALUES (?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$usuario_id, $producto_id]);
                $esFavorito = true;
            }

            $this->db->commit();
            return $esFavorito;

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error en toggleFavorito: " . $e->getMessage());
            return false;
        }
    }

    public function esFavorito($usuario_id, $producto_id) {
        try {
            $sql = "SELECT COUNT(*) as total FROM favoritos WHERE usuario_id = ? AND producto_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id, $producto_id]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'] > 0;
        } catch (PDOException $e) {
            error_log("Error en esFavorito: " . $e->getMessage());
            return false;
        }
    }

    public function getAll($pagina = 1, $busqueda = '', $por_pagina = 10) {
        try {
            $params = [];
            $where = [];
            
            if ($busqueda) {
                $where[] = "(p.nombre LIKE :busqueda OR p.descripcion LIKE :busqueda OR p.marca LIKE :busqueda)";
                $params[':busqueda'] = "%$busqueda%";
            }
            
            $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
            
            // Contar total de registros
            $sqlCount = "SELECT COUNT(*) FROM productos p $whereClause";
            $stmtCount = $this->db->prepare($sqlCount);
            foreach ($params as $key => $value) {
                $stmtCount->bindValue($key, $value);
            }
            $stmtCount->execute();
            $total = $stmtCount->fetchColumn();
            
            // Calcular offset
            $offset = ($pagina - 1) * $por_pagina;
            
            // Obtener productos
            $sql = "SELECT p.*, c.nombre as categoria_nombre 
                    FROM productos p 
                    LEFT JOIN categorias c ON p.categoria_id = c.categoria_id 
                    $whereClause
                    ORDER BY p.fecha_creacion DESC
                    LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $por_pagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            
            return [
                'items' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                'total' => $total,
                'paginas' => ceil($total / $por_pagina)
            ];
        } catch (PDOException $e) {
            error_log("Error en getAll: " . $e->getMessage());
            return [
                'items' => [],
                'total' => 0,
                'paginas' => 1
            ];
        }
    }

    public function contarTotal() {
        try {
            $sql = "SELECT COUNT(*) FROM productos WHERE activo = 1";
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
            $sql = "INSERT INTO productos (
                        nombre, descripcion, precio, categoria_id, 
                        stock, grado_alcoholico, pais_origen, marca,
                        destacado, activo, imagen_url, fecha_creacion
                    ) VALUES (
                        :nombre, :descripcion, :precio, :categoria_id,
                        :stock, :grado_alcoholico, :pais_origen, :marca,
                        :destacado, :activo, :imagen_url, NOW()
                    )";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':nombre', $data['nombre']);
            $stmt->bindValue(':descripcion', $data['descripcion']);
            $stmt->bindValue(':precio', $data['precio']);
            $stmt->bindValue(':categoria_id', $data['categoria_id']);
            $stmt->bindValue(':stock', $data['stock']);
            $stmt->bindValue(':grado_alcoholico', $data['grado_alcoholico']);
            $stmt->bindValue(':pais_origen', $data['pais_origen']);
            $stmt->bindValue(':marca', $data['marca']);
            $stmt->bindValue(':destacado', $data['destacado']);
            $stmt->bindValue(':activo', $data['activo']);
            $stmt->bindValue(':imagen_url', $data['imagen_url'] ?? null);

            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error en create: " . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, $data) {
        try {
            $sql = "UPDATE productos SET 
                        nombre = :nombre,
                        descripcion = :descripcion,
                        precio = :precio,
                        categoria_id = :categoria_id,
                        stock = :stock,
                        grado_alcoholico = :grado_alcoholico,
                        pais_origen = :pais_origen,
                        marca = :marca,
                        destacado = :destacado,
                        activo = :activo,
                        imagen_url = :imagen_url
                    WHERE producto_id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':nombre', $data['nombre']);
            $stmt->bindValue(':descripcion', $data['descripcion']);
            $stmt->bindValue(':precio', $data['precio']);
            $stmt->bindValue(':categoria_id', $data['categoria_id']);
            $stmt->bindValue(':stock', $data['stock']);
            $stmt->bindValue(':grado_alcoholico', $data['grado_alcoholico']);
            $stmt->bindValue(':pais_origen', $data['pais_origen']);
            $stmt->bindValue(':marca', $data['marca']);
            $stmt->bindValue(':destacado', $data['destacado']);
            $stmt->bindValue(':activo', $data['activo']);
            $stmt->bindValue(':imagen_url', $data['imagen_url'] ?? null);
            $stmt->bindValue(':id', $id);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en update: " . $e->getMessage());
            throw $e;
        }
    }

    public function findById($id) {
        try {
            $sql = "SELECT p.*, c.nombre as categoria_nombre 
                    FROM productos p 
                    LEFT JOIN categorias c ON p.categoria_id = c.categoria_id 
                    WHERE p.producto_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);
            return $producto;
        } catch (PDOException $e) {
            error_log("Error en findById: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM productos WHERE producto_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error en delete: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarPromocion($producto_id, $precio_oferta) {
        $sql = "UPDATE productos SET precio_oferta = :precio_oferta WHERE producto_id = :producto_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':precio_oferta', $precio_oferta);
        $stmt->bindValue(':producto_id', $producto_id);
        return $stmt->execute();
    }

    public function getPrecioConPromocion($producto_id) {
        // Buscar promociones activas y vigentes para este producto
        $hoy = date('Y-m-d');
        $sql = "SELECT p.precio, pr.descuento_porcentaje
                FROM productos p
                LEFT JOIN promociones_productos pp ON p.producto_id = pp.producto_id
                LEFT JOIN promociones pr ON pp.promocion_id = pr.promocion_id
                WHERE p.producto_id = ?
                  AND pr.activo = 1
                  AND pr.fecha_inicio <= ?
                  AND pr.fecha_fin >= ?
                ORDER BY pr.descuento_porcentaje DESC
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$producto_id, $hoy, $hoy]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && $row['descuento_porcentaje'] > 0) {
            return round($row['precio'] * (1 - $row['descuento_porcentaje'] / 100), 2);
        } else {
            // Si no hay promoción, devolver el precio normal
            $sql = "SELECT precio FROM productos WHERE producto_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$producto_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $row['precio'] : 0;
        }
    }
}
?> 