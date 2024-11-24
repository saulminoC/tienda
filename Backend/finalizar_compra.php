<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$carrito = $data['carrito'] ?? [];

if (empty($carrito)) {
    echo json_encode(['success' => false, 'message' => 'El carrito está vacío.']);
    exit;
}

$conn->begin_transaction();

try {
    // ID del usuario (simulado, reemplázalo con el usuario actual)
    $usuario_id = 1;

    // Calcular el total del carrito
    $total = 0;
    foreach ($carrito as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }

    // Insertar el pedido
    $sqlPedido = "INSERT INTO pedidos (usuario_id, total) VALUES (?, ?)";
    $stmtPedido = $conn->prepare($sqlPedido);
    $stmtPedido->bind_param("id", $usuario_id, $total);
    $stmtPedido->execute();
    $pedido_id = $stmtPedido->insert_id;

    // Insertar los detalles del pedido
    $sqlDetalle = "INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)";
    $stmtDetalle = $conn->prepare($sqlDetalle);

    foreach ($carrito as $item) {
        $stmtDetalle->bind_param("iiid", $pedido_id, $item['id'], $item['cantidad'], $item['precio']);
        $stmtDetalle->execute();
    }

    // Confirmar la transacción
    $conn->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Error al procesar el pedido: ' . $e->getMessage()]);
}

$conn->close();
?>
