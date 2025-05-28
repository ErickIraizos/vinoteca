<?php
require_once 'Model.php';

class CarritoModel extends Model {
    public function __construct($db) {
        parent::__construct($db);
        $this->table = 'carritos';
    }

    private function obtenerOCrearCarrito($usuario_id) {
        // Intentar obtener el carrito existente
        $sql = "SELECT * FROM carritos WHERE usuario_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id]);
        $carrito = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si no existe, crear uno nuevo
        if (!$carrito) {
            $sql = "INSERT INTO carritos (usuario_id) VALUES (?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id]);
            
            return [
                'carrito_id' => $this->db->lastInsertId(),
                'usuario_id' => $usuario_id
            ];
        }

        return $carrito;
    }

    public function getItems($usuario_id) {
        try {
            $carrito = $this->obtenerOCrearCarrito($usuario_id);
            $sql = "SELECT ic.*, p.nombre, p.precio, p.imagen_url, p.stock, p.producto_id 
                    FROM items_carrito ic 
                    INNER JOIN productos p ON ic.producto_id = p.producto_id 
                    WHERE ic.carrito_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$carrito['carrito_id']]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Obtener modelo producto
            require_once 'models/ProductoModel.php';
            $productoModel = new ProductoModel($this->db);
            foreach ($items as &$item) {
                $item['precio_final'] = $productoModel->getPrecioConPromocion($item['producto_id']);
            }
            return $items;
        } catch (PDOException $e) {
            error_log("Error en getItems: " . $e->getMessage());
            return [];
        }
    }

    public function agregarItem($usuario_id, $producto_id, $cantidad = 1) {
        try {
            // Obtener o crear el carrito
            $carrito = $this->obtenerOCrearCarrito($usuario_id);
            
            // Verificar si el producto ya está en el carrito
            $sql = "SELECT cantidad FROM items_carrito 
                    WHERE carrito_id = ? AND producto_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$carrito['carrito_id'], $producto_id]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($item) {
                // Actualizar cantidad
                $sql = "UPDATE items_carrito 
                        SET cantidad = cantidad + ? 
                        WHERE carrito_id = ? AND producto_id = ?";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([$cantidad, $carrito['carrito_id'], $producto_id]);
            } else {
                // Insertar nuevo item
                $sql = "INSERT INTO items_carrito (carrito_id, producto_id, cantidad) 
                        VALUES (?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([$carrito['carrito_id'], $producto_id, $cantidad]);
            }
        } catch (PDOException $e) {
            error_log("Error en agregarItem: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarCantidad($usuario_id, $producto_id, $cantidad) {
        try {
            $carrito = $this->obtenerOCrearCarrito($usuario_id);

            if ($cantidad <= 0) {
                return $this->eliminarItem($usuario_id, $producto_id);
            }

            $sql = "UPDATE items_carrito 
                    SET cantidad = ? 
                    WHERE carrito_id = ? AND producto_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$cantidad, $carrito['carrito_id'], $producto_id]);
        } catch (PDOException $e) {
            error_log("Error en actualizarCantidad: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarItem($usuario_id, $producto_id) {
        try {
            $carrito = $this->obtenerOCrearCarrito($usuario_id);

            $sql = "DELETE FROM items_carrito 
                    WHERE carrito_id = ? AND producto_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$carrito['carrito_id'], $producto_id]);
        } catch (PDOException $e) {
            error_log("Error en eliminarItem: " . $e->getMessage());
            return false;
        }
    }

    public function vaciar($usuario_id) {
        try {
            $carrito = $this->obtenerOCrearCarrito($usuario_id);

            $sql = "DELETE FROM items_carrito WHERE carrito_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$carrito['carrito_id']]);
        } catch (PDOException $e) {
            error_log("Error en vaciar: " . $e->getMessage());
            return false;
        }
    }

    public function getTotal($usuario_id) {
        try {
            $carrito = $this->obtenerOCrearCarrito($usuario_id);
            $sql = "SELECT ic.producto_id, ic.cantidad 
                    FROM items_carrito ic 
                    WHERE ic.carrito_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$carrito['carrito_id']]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            require_once 'models/ProductoModel.php';
            $productoModel = new ProductoModel($this->db);
            $total = 0;
            foreach ($items as $item) {
                $precio = $productoModel->getPrecioConPromocion($item['producto_id']);
                $total += $precio * $item['cantidad'];
            }
            return $total;
        } catch (PDOException $e) {
            error_log("Error en getTotal: " . $e->getMessage());
            return 0;
        }
    }
} 