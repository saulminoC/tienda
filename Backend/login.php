<?php
session_start();
header('Content-Type: application/json');

// Conexión a la base de datos
$servername = "localhost"; // Cambia si es necesario
$username = "root";        // Usuario de la base de datos
$password = "";            // Contraseña
$dbname = "tienda";        // Nombre de la base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error al conectar a la base de datos.']);
    exit;
}

// Leer datos enviados por el formulario
$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

// Validar entrada
if (empty($email) || empty($password)) {
    echo json_encode(['exito' => false, 'mensaje' => 'Todos los campos son obligatorios.']);
    exit;
}

// Consultar usuario en la base de datos
$sql = "SELECT id, email, password, nombre FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    // Verificar contraseña
    if (password_verify($password, $usuario['password'])) {
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_email'] = $usuario['email'];
        $_SESSION['user_name'] = $usuario['nombre'];

        // Redirigir según el correo electrónico
        if ($usuario['email'] === 'admin@tuempresa.com') { // Cambia este correo por el del administrador
            echo json_encode(['exito' => true, 'mensaje' => 'Inicio de sesión como administrador.', 'redirect' => '/tienda/Frontend/web/admin_productos.html']);
        } else {
            echo json_encode(['exito' => true, 'mensaje' => 'Inicio de sesión exitoso.', 'redirect' => '/tienda/Frontend/web/inicio.html']);
        }
    } else {
        echo json_encode(['exito' => false, 'mensaje' => 'Contraseña incorrecta.']);
    }
} else {
    echo json_encode(['exito' => false, 'mensaje' => 'Correo electrónico no registrado.']);
}

$stmt->close();
$conn->close();
?>
