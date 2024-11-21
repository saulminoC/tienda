<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'];
$password = $data['password'];

$sql = "SELECT id, nombre, password FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

echo password_hash('contraseña', PASSWORD_DEFAULT);
$inputPassword = 'contraseña';
$storedHash = '$2y$10$8sP2xd3X9TtZZjBuvQaLFeQ8.CfCjs1WTsCFrrbgVVHOVaT53gKWm';

if (password_verify($inputPassword, $storedHash)) {
    echo "La contraseña es correcta.";
} else {
    echo "La contraseña no coincide.";
}

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        echo json_encode(["success" => true, "message" => "Inicio de sesión exitoso", "user_id" => $user['id']]);
    } else {
        echo json_encode(["success" => false, "message" => "Contraseña incorrecta"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Usuario no encontrado"]);
}
$stmt->close();
$conn->close();
?>
