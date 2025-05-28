<?php

class DireccionModel {
    public function eliminarPorUsuario($usuario_id) {
        try {
            $sql = "DELETE FROM direcciones WHERE usuario_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$usuario_id]);
        } catch (PDOException $e) {
            error_log("Error en eliminarPorUsuario (direcciones): " . $e->getMessage());
            return false;
        }
    }
} 