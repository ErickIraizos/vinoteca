<?php
require_once 'vendor/autoload.php';

class ReportePDF extends TCPDF {
    protected $titulo;
    protected $periodo;

    public function __construct($titulo, $periodo) {
        parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->titulo = $titulo;
        $this->periodo = $periodo;
        
        // Configuración del documento
        $this->SetCreator('Vino E-commerce');
        $this->SetAuthor('Administrador');
        $this->SetTitle($titulo);
        
        // Configuración de márgenes
        $this->SetMargins(15, 15, 15);
        $this->SetAutoPageBreak(TRUE, 15);
        
        // Configuración de fuente
        $this->SetFont('helvetica', '', 10);
    }

    public function Header() {
        // Eliminar logo
        // Título
        $this->SetFont('helvetica', 'B', 20);
        $this->Cell(0, 15, $this->titulo, 0, false, 'C', 0);
        $this->Ln(10);
        // Período
        $this->SetFont('helvetica', '', 12);
        $this->Cell(0, 10, $this->periodo, 0, false, 'C', 0);
        $this->Ln(20);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0);
    }

    public function addEstadisticas($stats) {
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(0, 10, 'Resumen General', 0, 1, 'L');
        $this->Ln(5);

        $this->SetFont('helvetica', '', 12);
        $w = ($this->GetPageWidth() - 30) / 2;

        // Primera fila
        $this->Cell($w, 10, 'Ventas Totales:', 0, 0, 'L');
        $this->Cell($w, 10, number_format($stats['ventas_totales'], 2, ',', '.') . ' €', 0, 1, 'R');
        
        $this->Cell($w, 10, 'Pedidos Completados:', 0, 0, 'L');
        $this->Cell($w, 10, $stats['pedidos_completados'], 0, 1, 'R');
        
        $this->Cell($w, 10, 'Productos Vendidos:', 0, 0, 'L');
        $this->Cell($w, 10, $stats['productos_vendidos'], 0, 1, 'R');
        
        $this->Cell($w, 10, 'Nuevos Clientes:', 0, 0, 'L');
        $this->Cell($w, 10, $stats['nuevos_clientes'], 0, 1, 'R');

        $this->Ln(10);
    }

    public function addTablaVentas($datos) {
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(0, 10, 'Detalle de Ventas', 0, 1, 'L');
        $this->Ln(5);

        // Encabezados
        $this->SetFont('helvetica', 'B', 11);
        $this->SetFillColor(240, 240, 240);
        $this->Cell(60, 8, 'Fecha', 1, 0, 'C', true);
        $this->Cell(60, 8, 'Total Pedidos', 1, 0, 'C', true);
        $this->Cell(60, 8, 'Total Ventas', 1, 1, 'C', true);

        // Datos
        $this->SetFont('helvetica', '', 10);
        foreach ($datos as $fila) {
            $this->Cell(60, 8, $fila['fecha'], 1, 0, 'C');
            $this->Cell(60, 8, $fila['total_pedidos'], 1, 0, 'C');
            $this->Cell(60, 8, number_format($fila['total_ventas'], 2, ',', '.') . ' €', 1, 1, 'R');
        }
    }

    public function addTablaProductos($datos) {
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(0, 10, 'Productos Más Vendidos', 0, 1, 'L');
        $this->Ln(5);

        // Encabezados
        $this->SetFont('helvetica', 'B', 11);
        $this->SetFillColor(240, 240, 240);
        $this->Cell(80, 8, 'Producto', 1, 0, 'C', true);
        $this->Cell(50, 8, 'Cantidad', 1, 0, 'C', true);
        $this->Cell(50, 8, 'Total Ventas', 1, 1, 'C', true);

        // Datos
        $this->SetFont('helvetica', '', 10);
        foreach ($datos as $fila) {
            $this->Cell(80, 8, $fila['nombre'], 1, 0, 'L');
            $this->Cell(50, 8, $fila['cantidad_total'], 1, 0, 'C');
            $this->Cell(50, 8, number_format($fila['total_ventas'], 2, ',', '.') . ' €', 1, 1, 'R');
        }
    }

    public function addTablaUsuarios($datos) {
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(0, 10, 'Usuarios que Más Compran', 0, 1, 'L');
        $this->Ln(5);

        // Encabezados
        $this->SetFont('helvetica', 'B', 11);
        $this->SetFillColor(240, 240, 240);
        $this->Cell(60, 8, 'Nombre', 1, 0, 'C', true);
        $this->Cell(60, 8, 'Email', 1, 0, 'C', true);
        $this->Cell(30, 8, 'Pedidos', 1, 0, 'C', true);
        $this->Cell(30, 8, 'Total', 1, 1, 'C', true);

        // Datos
        $this->SetFont('helvetica', '', 10);
        foreach ($datos as $fila) {
            $this->Cell(60, 8, $fila['nombre'], 1, 0, 'L');
            $this->Cell(60, 8, $fila['email'], 1, 0, 'L');
            $this->Cell(30, 8, $fila['total_pedidos'], 1, 0, 'C');
            $this->Cell(30, 8, number_format($fila['total_compras'], 2, ',', '.') . ' €', 1, 1, 'R');
        }
    }
} 