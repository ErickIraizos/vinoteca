<?php

class Database {
    private $host = 'localhost';
    private $db_name = 'licoreria_online';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Debug de la conexión
            error_log("Intentando conectar a la base de datos: {$this->db_name} en {$this->host}");

            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("SET NAMES utf8");

            // Verificar la conexión
            $this->conn->query("SELECT 1");
            error_log("Conexión exitosa a la base de datos");

            return $this->conn;
        } catch (PDOException $e) {
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            throw new Exception("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
}
?> 