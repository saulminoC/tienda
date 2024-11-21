<?php
header('Content-Type: application/json');
require 'db.php';

// Recibir datos desde el Frontend
$data = json_decode(file_get_contents("php://input"), true);

$usuario_id = $data['usuario_id']; // ID del usuario
$producto_id = $data['producto_id']; // ID del producto
$cantidad = $data['cantidad']; // Cantidad a agregar

// Verificar si el producto ya está en el carrito
$sql = "SELECT id, cantidad FROM carritos WHERE usuario_id = ? AND producto_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $usuario_id, $producto_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Si el producto ya está en el carrito, actualiza la cantidad
    $row = $result->fetch_assoc();
    $nueva_cantidad = $row['cantidad'] + $cantidad;

    $update_sql = "UPDATE carritos SET cantidad = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $nueva_cantidad, $row['id']);
    $update_stmt->execute();
    echo json_encode(["mensaje" => "Producto actualizado en el carrito"]);
} else {
    // Si no está en el carrito, agrégalo
    $insert_sql = "INSERT INTO carritos (usuario_id, producto_id, cantidad) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iii", $usuario_id, $producto_id, $cantidad);
    $insert_stmt->execute();
    echo json_encode(["mensaje" => "Producto agregado al carrito"]);
}

$stmt->close();
$conn->close();
?>
