<?php

class Database {
    private $conn;

    public function getConnection() {
        $this->conn = null;

        // Obtener las variables de entorno desde Railway
        $host = getenv('nozomi.proxy.rlwy.net');
        $db_name = getenv('railway');
        $username = getenv('root');
        $password = getenv('MNTdyKiSJJWTqiQFyUPpsCahjWuIHENa');
        $port = getenv('59630') ?: 3306; // Usa 3306 por defecto si no está definido

        try {
            // Debug de la conexión
            error_log("Conectando a DB en $host:$port, base de datos: $db_name");

            $this->conn = new PDO(
                "mysql:host=$host;port=$port;dbname=$db_name;charset=utf8",
                $username,
                $password
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Verificar la conexión
            $this->conn->query("SELECT 1");
            error_log("Conexión exitosa a la base de datos Railway");

            return $this->conn;

        } catch (PDOException $e) {
            error_log("Error de conexión: " . $e->getMessage());
            throw new Exception("Error al conectar a la base de datos: " . $e->getMessage());
        }
    }
}
?>
