-- Añadir campo rol a la tabla usuarios
ALTER TABLE usuarios
ADD COLUMN rol VARCHAR(20) NOT NULL DEFAULT 'usuario';

-- Actualizar al menos un usuario como administrador
UPDATE usuarios 
SET rol = 'admin' 
WHERE email = 'admin@example.com' 
   OR usuario_id = 1; 