<?php
session_start();
header('Content-Type: application/json');
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$nombre = $data['nombre'];
$password = $data['password'];
$user_id = $_SESSION['user_id'];

// Actualizar el perfil
$sql = "UPDATE usuarios SET nombre = ?" . ($password ? ", password = ?" : "") . " WHERE id = ?";
$stmt = $conn->prepare($password ? $sql : str_replace(", password = ?", "", $sql));

if ($password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bind_param("ssi", $nombre, $hashedPassword, $user_id);
} else {
    $stmt->bind_param("si", $nombre, $user_id);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Perfil actualizado correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el perfil.']);
}

$stmt->close();
$conn->close();
?>
