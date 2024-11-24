<?php
session_start();
header('Content-Type: application/json');
require 'db.php';

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado.']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Obtener datos del usuario
$sql = "SELECT nombre, email FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode(['success' => true, 'nombre' => $user['nombre'], 'email' => $user['email']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
}

$stmt->close();
$conn->close();
?>
