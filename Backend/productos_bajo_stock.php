<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);


$conn = new mysqli("localhost", "root", "", "tienda");
if ($conn->connect_error) {
    echo json_encode(['error' => 'Error de conexión a la base de datos.']);
    exit;
}

// Productos con bajo stock
$sqlBajoStock = "SELECT nombre, stock FROM productos WHERE stock < 10 ORDER BY stock ASC";
$result = $conn->query($sqlBajoStock);

$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

echo json_encode($productos);
$conn->close();
?>
