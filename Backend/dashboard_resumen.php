<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tienda";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Error de conexión a la base de datos.']);
    exit;
}

// Ventas totales
$sqlVentas = "SELECT SUM(total) AS ventasTotales FROM pedidos";
$resultVentas = $conn->query($sqlVentas);
$ventasTotales = $resultVentas->fetch_assoc()['ventasTotales'] ?? 0;

// Pedidos totales
$sqlPedidos = "SELECT COUNT(*) AS pedidosTotales FROM pedidos";
$resultPedidos = $conn->query($sqlPedidos);
$pedidosTotales = $resultPedidos->fetch_assoc()['pedidosTotales'] ?? 0;

// Usuarios registrados
$sqlUsuarios = "SELECT COUNT(*) AS usuariosRegistrados FROM usuarios";
$resultUsuarios = $conn->query($sqlUsuarios);
$usuariosRegistrados = $resultUsuarios->fetch_assoc()['usuariosRegistrados'] ?? 0;

// Respuesta
echo json_encode([
    'ventasTotales' => $ventasTotales,
    'pedidosTotales' => $pedidosTotales,
    'usuariosRegistrados' => $usuariosRegistrados
]);
$conn->close();
?>
