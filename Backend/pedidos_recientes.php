<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);


$conn = new mysqli("localhost", "root", "", "tienda");
if ($conn->connect_error) {
    echo json_encode(['error' => 'Error de conexión a la base de datos.']);
    exit;
}

// Pedidos recientes
$sqlPedidos = "SELECT id, fecha, total FROM pedidos ORDER BY fecha DESC LIMIT 5";
$result = $conn->query($sqlPedidos);

$pedidos = [];
while ($row = $result->fetch_assoc()) {
    $pedidos[] = $row;
}

echo json_encode($pedidos);
$conn->close();
?>
