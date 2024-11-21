<?php
header('Content-Type: application/json');
require 'db.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos

$sql = "SELECT id, nombre, descripcion, precio, imagen FROM productos";
$result = $conn->query($sql);

$productos = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
}

// Devolver los productos como JSON
echo json_encode($productos);

$conn->close();
?>
