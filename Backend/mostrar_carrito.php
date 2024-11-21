<?php
header('Content-Type: application/json');
require 'db.php';

$usuario_id = isset($_GET['usuario_id']) ? intval($_GET['usuario_id']) : 0;

// Consulta para obtener productos del carrito
$sql = "SELECT c.id, p.nombre, p.precio, c.cantidad, (p.precio * c.cantidad) AS total
        FROM carritos c
        JOIN productos p ON c.producto_id = p.id
        WHERE c.usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$carrito = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $carrito[] = $row;
    }
}

echo json_encode($carrito);
$stmt->close();
$conn->close();
?>
