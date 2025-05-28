<?php
class Model {
    protected $db;
    protected $table;

    public function __construct($db) {
        if (!$db) {
            error_log("Error: No se proporcionó una conexión a la base de datos");
            throw new Exception("Error de conexión a la base de datos");
        }
        $this->db = $db;

        // Verificar la conexión
        try {
            $this->db->query("SELECT 1");
            error_log("Conexión a la base de datos establecida correctamente");
        } catch (PDOException $e) {
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            throw new Exception("Error de conexión a la base de datos");
        }
    }

    public function findAll($conditions = [], $order = '', $limit = null) {
        $sql = "SELECT * FROM {$this->table}";
        
        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $whereClauses = [];
            foreach ($conditions as $key => $value) {
                $whereClauses[] = "$key = :$key";
            }
            $sql .= implode(' AND ', $whereClauses);
        }
        
        if ($order) {
            $sql .= " ORDER BY $order";
        }
        
        if ($limit) {
            $sql .= " LIMIT $limit";
        }

        $stmt = $this->db->prepare($sql);
        
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $idColumn = property_exists($this, 'primaryKey') ? $this->primaryKey : rtrim($this->table, 's') . '_id';
        $sql = "SELECT * FROM {$this->table} WHERE {$idColumn} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        try {
            $fields = implode(', ', array_keys($data));
            $values = ':' . implode(', :', array_keys($data));
            
            $sql = "INSERT INTO {$this->table} ($fields) VALUES ($values)";
            $stmt = $this->db->prepare($sql);
            
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en create: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        $idColumn = property_exists($this, 'primaryKey') ? $this->primaryKey : $this->table . '_id';
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $fields = implode(', ', $fields);
        $sql = "UPDATE {$this->table} SET $fields WHERE {$idColumn} = :id";
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }

    public function delete($id) {
        $idColumn = property_exists($this, 'primaryKey') ? $this->primaryKey : $this->table . '_id';
        $sql = "DELETE FROM {$this->table} WHERE {$idColumn} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function count($conditions = []) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        
        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $whereClauses = [];
            foreach ($conditions as $key => $value) {
                $whereClauses[] = "$key = :$key";
            }
            $sql .= implode(' AND ', $whereClauses);
        }

        $stmt = $this->db->prepare($sql);
        
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function getPDO() {
        return $this->db;
    }

    public function setTable($table) {
        $this->table = $table;
    }
}
?> 