<?php
header('Content-Type: application/json');

// Registrar datos para depuración
$raw_data = file_get_contents("php://input");
file_put_contents("debug_data.log", $raw_data);

// Decodificar datos
$data = json_decode($raw_data, true);

if (!$data || !isset($data['total']) || !isset($data['productos'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'Datos inválidos.']);
    exit;
}

$total = (float)$data['total'];
$productos = json_encode($data['productos'], JSON_UNESCAPED_UNICODE);

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "tienda");
if ($conn->connect_error) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error de conexión a la base de datos.']);
    exit;
}

// Insertar pedido
$sql = "INSERT INTO pedidos (total, fecha, detalles) VALUES (?, NOW(), ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ds", $total, $productos);

if ($stmt->execute()) {
    $pedido_id = $stmt->insert_id;
    echo json_encode(['exito' => true, 'mensaje' => 'Pedido confirmado con éxito.', 'pedido_id' => $pedido_id]);
} else {
    echo json_encode(['exito' => false, 'mensaje' => 'Error al guardar el pedido.']);
}

$stmt->close();
$conn->close();
?>
