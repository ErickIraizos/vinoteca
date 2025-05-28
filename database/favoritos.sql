CREATE TABLE IF NOT EXISTS `favoritos` (
  `favorito_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`favorito_id`),
  UNIQUE KEY `usuario_producto` (`usuario_id`, `producto_id`),
  KEY `fk_favoritos_usuario` (`usuario_id`),
  KEY `fk_favoritos_producto` (`producto_id`),
  CONSTRAINT `fk_favoritos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_favoritos_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`producto_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 