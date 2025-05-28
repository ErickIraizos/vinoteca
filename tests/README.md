# Suite de Pruebas - Sistema de Vinos

Esta carpeta contiene las pruebas automatizadas para el sistema de gestión de vinos.

## Estructura

- `config.php`: Configuración del entorno de pruebas
- `test_db.php`: Pruebas de base de datos
- `test_security.php`: Pruebas de seguridad
- `run_tests.php`: Script principal para ejecutar todas las pruebas
- `logs/`: Carpeta donde se guardan los logs y reportes

## Cómo ejecutar las pruebas

1. Asegúrate de tener una base de datos de prueba configurada
2. Desde la línea de comandos, ejecuta:
   ```bash
   php tests/run_tests.php
   ```

## Resultados

Los resultados de las pruebas se guardan en:
- Logs diarios: `logs/test_YYYY-MM-DD.log`
- Reportes: `logs/report_YYYY-MM-DD_HH-mm-ss.txt`

## Notas importantes

- Las pruebas no afectan a la base de datos principal
- Se crea una base de datos de prueba separada
- Los logs se guardan en la carpeta `logs/`
- Se pueden eliminar todos los archivos de prueba sin afectar al sistema

## Eliminación

Para eliminar las pruebas:
1. Detener cualquier prueba en ejecución
2. Eliminar la carpeta `tests/`
3. Eliminar la base de datos de prueba si se creó 