<?php
require_once 'Model.php';

class UsuarioModel extends Model {
    public function __construct($db) {
        parent::__construct($db);
        $this->table = 'usuarios';
    }

    public function getByEmail($email) {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getByEmail: " . $e->getMessage());
            return false;
        }
    }

    public function emailExiste($email) {
        try {
            $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['email' => $email]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error en emailExiste: " . $e->getMessage());
            return false;
        }
    }

    public function crear($data) {
        try {
            $sql = "INSERT INTO usuarios (email, password, nombre, apellido, direccion, telefono, 
                    fecha_nacimiento, token_verificacion, verificado, rol) 
                    VALUES (:email, :password, :nombre, :apellido, :direccion, :telefono, 
                    :fecha_nacimiento, :token_verificacion, 0, 'cliente')";
            
            error_log("Intentando crear usuario con email: " . $data['email']);
            
            $stmt = $this->db->prepare($sql);
            $params = [
                'email' => $data['email'],
                'password' => $data['password'],
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'direccion' => $data['direccion'] ?? null,
                'telefono' => $data['telefono'] ?? null,
                'fecha_nacimiento' => $data['fecha_nacimiento'] ?? null,
                'token_verificacion' => $data['token_verificacion']
            ];
            
            error_log("Parámetros de inserción: " . print_r($params, true));
            
            $result = $stmt->execute($params);

            if ($result) {
                $userId = $this->db->lastInsertId();
                error_log("Usuario creado exitosamente con ID: " . $userId);
                return $userId;
            }
            
            error_log("Error al crear usuario: execute() retornó false");
            return false;
        } catch (PDOException $e) {
            error_log("Error en crear usuario: " . $e->getMessage());
            error_log("SQL State: " . $e->errorInfo[0]);
            error_log("Error Code: " . $e->errorInfo[1]);
            error_log("Error Message: " . $e->errorInfo[2]);
            return false;
        }
    }

    public function getByVerificationToken($token) {
        $sql = "SELECT * FROM usuarios WHERE token_verificacion = :token AND verificado = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function verificarEmail($usuarioId) {
        $sql = "UPDATE usuarios SET verificado = 1, token_verificacion = NULL WHERE usuario_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $usuarioId]);
    }

    public function guardarTokenRecordar($usuarioId, $token) {
        $sql = "UPDATE usuarios SET token_verificacion = :token WHERE usuario_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'token' => $token,
            'id' => $usuarioId
        ]);
    }

    public function guardarTokenRecuperacion($usuarioId, $token, $expiry) {
        $sql = "UPDATE usuarios SET token_verificacion = :token WHERE usuario_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'token' => $token,
            'id' => $usuarioId
        ]);
    }

    public function getByResetToken($token) {
        $sql = "SELECT * FROM usuarios WHERE token_verificacion = :token";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarPassword($usuarioId, $password) {
        $sql = "UPDATE usuarios SET password = :password, token_verificacion = NULL WHERE usuario_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'password' => $password,
            'id' => $usuarioId
        ]);
    }

    public function getDetalle($id) {
        $sql = "SELECT usuario_id, nombre, apellido, email, direccion, telefono, fecha_nacimiento, fecha_registro 
                FROM usuarios 
                WHERE usuario_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function contarTotal() {
        try {
            $sql = "SELECT COUNT(*) as total FROM usuarios WHERE activo = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            error_log("Error en contarTotal: " . $e->getMessage());
            return 0;
        }
    }

    public function obtenerUltimos($limite = 5) {
        try {
            $sql = "SELECT usuario_id, nombre, email, fecha_registro, rol 
                    FROM usuarios 
                    WHERE activo = 1 
                    ORDER BY fecha_registro DESC 
                    LIMIT ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limite]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerUltimos: " . $e->getMessage());
            return [];
        }
    }

    public function esAdmin($usuario_id) {
        try {
            $sql = "SELECT rol FROM usuarios WHERE usuario_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado && $resultado['rol'] === 'admin';
        } catch (PDOException $e) {
            error_log("Error en esAdmin: " . $e->getMessage());
            return false;
        }
    }

    public function getAll($pagina = 1, $busqueda = '', $por_pagina = 10) {
        try {
            $params = [];
            $where = [];
            
            if ($busqueda) {
                $where[] = "(nombre LIKE :busqueda OR email LIKE :busqueda OR apellido LIKE :busqueda)";
                $params[':busqueda'] = "%$busqueda%";
            }
            
            $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
            
            // Contar total de registros
            $sqlCount = "SELECT COUNT(*) FROM usuarios $whereClause";
            $stmtCount = $this->db->prepare($sqlCount);
            foreach ($params as $key => $value) {
                $stmtCount->bindValue($key, $value);
            }
            $stmtCount->execute();
            $total = $stmtCount->fetchColumn();
            
            // Calcular offset
            $offset = ($pagina - 1) * $por_pagina;
            
            // Obtener usuarios
            $sql = "SELECT usuario_id, nombre, apellido, email, rol, activo, fecha_registro 
                    FROM usuarios 
                    $whereClause
                    ORDER BY fecha_registro DESC
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

    public function update($id, $data) {
        try {
            $fields = [];
            foreach ($data as $key => $value) {
                $fields[] = "$key = :$key";
            }
            $fields = implode(', ', $fields);
            
            $sql = "UPDATE usuarios SET $fields WHERE usuario_id = :id";
            $stmt = $this->db->prepare($sql);
            
            // Añadir el ID a los datos
            $data['id'] = $id;
            
            // Bind de todos los parámetros
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en update: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM usuarios WHERE usuario_id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Error en delete: " . $e->getMessage());
            return false;
        }
    }

    public function findById($id) {
        try {
            $sql = "SELECT * FROM usuarios WHERE usuario_id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en findById: " . $e->getMessage());
            return false;
        }
    }
} 