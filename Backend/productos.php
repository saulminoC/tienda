<?php
header('Content-Type: application/json');
require 'db.php'; // Archivo que conecta con la base de datos

// Obtener todos los productos de la base de datos
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);

$productos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos[] = [
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'descripcion' => $row['descripcion'],
            'precio' => $row['precio'],
            'imagen' => $row['imagen']
        ];
    }
}

echo json_encode($productos);
$conn->close();
?>
