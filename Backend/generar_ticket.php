<?php
require('fpdf/fpdf.php');
header('Content-Type: application/pdf');

// Verificar si se proporciona el ID del pedido
if (!isset($_GET['pedido_id']) || !is_numeric($_GET['pedido_id'])) {
    die('Pedido ID inválido.');
}

$pedido_id = (int)$_GET['pedido_id'];

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "tienda");
if ($conn->connect_error) {
    die("Error de conexión a la base de datos.");
}

// Consultar datos del pedido
$sqlPedido = "SELECT id, fecha, total, detalles FROM pedidos WHERE id = ?";
$stmtPedido = $conn->prepare($sqlPedido);
$stmtPedido->bind_param("i", $pedido_id);
$stmtPedido->execute();
$resultPedido = $stmtPedido->get_result();

if ($resultPedido->num_rows === 0) {
    die("No se encontró el pedido.");
}

$pedido = $resultPedido->fetch_assoc();
$productos = json_decode($pedido['detalles'], true);

// Crear el PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Encabezado
$pdf->Cell(0, 10, 'Ticket de Compra', 0, 1, 'C');
$pdf->Ln(10);

// Detalles del pedido
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 10, 'Pedido ID: ' . $pedido['id']);
$pdf->Ln();
$pdf->Cell(50, 10, 'Fecha: ' . $pedido['fecha']);
$pdf->Ln();
$pdf->Cell(50, 10, 'Total: $' . number_format($pedido['total'], 2));
$pdf->Ln(10);

// Tabla de productos
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(80, 10, 'Producto', 1);
$pdf->Cell(30, 10, 'Cantidad', 1);
$pdf->Cell(30, 10, 'Precio', 1);
$pdf->Cell(30, 10, 'Subtotal', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
foreach ($productos as $producto) {
    $subtotal = $producto['cantidad'] * $producto['precio'];
    $pdf->Cell(80, 10, $producto['nombre'], 1);
    $pdf->Cell(30, 10, $producto['cantidad'], 1);
    $pdf->Cell(30, 10, '$' . number_format($producto['precio'], 2), 1);
    $pdf->Cell(30, 10, '$' . number_format($subtotal, 2), 1);
    $pdf->Ln();
}

$stmtPedido->close();
$conn->close();

// Salida del PDF
$pdf->Output();
?>
