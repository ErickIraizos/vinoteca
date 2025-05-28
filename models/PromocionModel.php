<?php
require_once 'Model.php';
class PromocionModel extends Model {
    protected $table = 'promociones';

    public function getAll() {
        $sql = "SELECT * FROM promociones ORDER BY fecha_inicio DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $sql = "SELECT * FROM promociones WHERE promocion_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO promociones (nombre, descripcion, descuento_porcentaje, fecha_inicio, fecha_fin, activo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['descuento_porcentaje'],
            $data['fecha_inicio'],
            $data['fecha_fin'],
            $data['activo'] ?? 1
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE promociones SET nombre=?, descripcion=?, descuento_porcentaje=?, fecha_inicio=?, fecha_fin=?, activo=? WHERE promocion_id=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['descuento_porcentaje'],
            $data['fecha_inicio'],
            $data['fecha_fin'],
            $data['activo'] ?? 1,
            $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM promociones WHERE promocion_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Productos asociados a una promoción
    public function getProductos($promocion_id) {
        $sql = "SELECT p.* FROM productos p INNER JOIN promociones_productos pp ON p.producto_id = pp.producto_id WHERE pp.promocion_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$promocion_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function asociarProducto($promocion_id, $producto_id) {
        $sql = "INSERT IGNORE INTO promociones_productos (promocion_id, producto_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$promocion_id, $producto_id]);
    }
    public function desasociarProducto($promocion_id, $producto_id) {
        $sql = "DELETE FROM promociones_productos WHERE promocion_id = ? AND producto_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$promocion_id, $producto_id]);
    }
    public function limpiarProductos($promocion_id) {
        $sql = "DELETE FROM promociones_productos WHERE promocion_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$promocion_id]);
    }
} 