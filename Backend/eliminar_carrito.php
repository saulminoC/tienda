<?php
header('Content-Type: application/json');
require 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "DELETE FROM carritos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["mensaje" => "Producto eliminado del carrito"]);
} else {
    echo json_encode(["error" => "Error al eliminar el producto del carrito"]);
}

$stmt->close();
$conn->close();
?>
