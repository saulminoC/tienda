<?php
header("Content-Type: application/json");

$servername = "localhost"; // Servidor de la base de datos
$username = "root";        // Usuario de la base de datos (ajusta si es necesario)
$password = "";            // Contraseña (ajusta si es necesario)
$dbname = "tienda";        // Nombre de tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Error al conectar con la base de datos: " . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["nombre"], $data["descripcion"], $data["precio"], $data["categoria"], $data["stock"], $data["imagen"])) {
    $nombre = $conn->real_escape_string($data["nombre"]);
    $descripcion = $conn->real_escape_string($data["descripcion"]);
    $precio = $conn->real_escape_string($data["precio"]);
    $categoria = $conn->real_escape_string($data["categoria"]);
    $stock = $conn->real_escape_string($data["stock"]);
    $imagen = $conn->real_escape_string($data["imagen"]);

    $sql = "INSERT INTO productos (nombre, descripcion, precio, categoria, stock, imagen) 
            VALUES ('$nombre', '$descripcion', '$precio', '$categoria', '$stock', '$imagen')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["mensaje" => "Producto creado exitosamente"]);
    } else {
        echo json_encode(["error" => "Error al insertar el producto: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "Faltan datos en la solicitud"]);
}

$conn->close();
?>
