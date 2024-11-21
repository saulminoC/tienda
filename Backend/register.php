<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$nombre = $data['nombre'];
$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nombre, $email, $password);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Usuario registrado con éxito"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al registrar usuario"]);
}
$stmt->close();
$conn->close();
?>
