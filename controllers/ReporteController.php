<?php
class ReporteController extends Controller {
    private $reporteModel;
    private $pedidoModel;

    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->requireAdmin();
        
        $this->reporteModel = $this->loadModel('Reporte');
        $this->pedidoModel = $this->loadModel('Pedido');
    }

    public function exportar() {
        $tipo = $_GET['tipo'] ?? 'ventas';
        $desde = $_GET['desde'] ?? date('Y-m-d', strtotime('-30 days'));
        $hasta = $_GET['hasta'] ?? date('Y-m-d');
        $formato = $_GET['formato'] ?? 'csv';

        $datos = $this->reporteModel->getDatos($tipo, $desde, $hasta);
        
        // Establecer headers para la descarga
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=reporte_' . $tipo . '_' . date('Y-m-d') . '.csv');
        
        // Crear el archivo CSV
        $output = fopen('php://output', 'w');
        
        // Establecer el separador de columnas para Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM para UTF-8
        
        // Escribir encabezados según el tipo de reporte
        switch ($tipo) {
            case 'ventas':
                fputcsv($output, ['Fecha', 'Total de Pedidos', 'Total Ventas (€)']);
                foreach ($datos as $fila) {
                    fputcsv($output, [
                        $fila['fecha'],
                        $fila['total_pedidos'],
                        number_format($fila['total_ventas'], 2, ',', '.')
                    ]);
                }
                break;
                
            case 'productos':
                fputcsv($output, ['Producto', 'Cantidad Vendida', 'Total Ventas (€)']);
                foreach ($datos as $fila) {
                    fputcsv($output, [
                        $fila['nombre'],
                        $fila['cantidad_total'],
                        number_format($fila['total_ventas'], 2, ',', '.')
                    ]);
                }
                break;
                
            case 'usuarios':
                fputcsv($output, ['Nombre', 'Email', 'Total Pedidos', 'Total Compras (€)']);
                foreach ($datos as $fila) {
                    fputcsv($output, [
                        $fila['nombre'],
                        $fila['email'],
                        $fila['total_pedidos'],
                        number_format($fila['total_compras'], 2, ',', '.')
                    ]);
                }
                break;
        }
        
        fclose($output);
        exit();
    }

    public function imprimir() {
        $tipo = $_GET['tipo'] ?? 'ventas';
        $desde = $_GET['desde'] ?? date('Y-m-d', strtotime('-30 days'));
        $hasta = $_GET['hasta'] ?? date('Y-m-d');

        $datos = $this->reporteModel->getDatos($tipo, $desde, $hasta);
        $stats = [
            'ventas_totales' => $this->reporteModel->getVentasTotales($desde, $hasta),
            'pedidos_completados' => $this->reporteModel->getPedidosCompletados($desde, $hasta),
            'productos_vendidos' => $this->reporteModel->getProductosVendidos($desde, $hasta),
            'nuevos_clientes' => $this->reporteModel->getNuevosClientes($desde, $hasta)
        ];

        // Cargar vista especial para imprimir
        $this->view('admin/reportes/imprimir', [
            'datos' => $datos,
            'stats' => $stats,
            'tipo' => $tipo,
            'desde' => $desde,
            'hasta' => $hasta,
            'title' => 'Imprimir Reporte'
        ]);
    }
} 