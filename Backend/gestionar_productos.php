<?php
header('Content-Type: application/json');
require_once('db.php');

$conexion = new mysqli($servername, $username, $password, $dbname);

if ($conexion->connect_error) {
    die(json_encode(['error' => 'Error de conexión a la base de datos']));
}

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo === 'GET') {
    // Listar productos
    $resultado = $conexion->query("SELECT * FROM productos");
    $productos = $resultado->fetch_all(MYSQLI_ASSOC);
    echo json_encode($productos);

} elseif ($metodo === 'POST') {
    // Crear o actualizar un producto
    $id = $_POST['id'] ?? null;
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $stock = $_POST['stock'];
    $imagen = $_POST['imagen'];

    if ($id) {
        // Actualizar producto
        $stmt = $conexion->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, categoria = ?, stock = ?, imagen = ? WHERE id = ?");
        $stmt->bind_param("ssdsssi", $nombre, $descripcion, $precio, $categoria, $stock, $imagen, $id);
    } else {
        // Crear producto
        $stmt = $conexion->prepare("INSERT INTO productos (nombre, descripcion, precio, categoria, stock, imagen) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsss", $nombre, $descripcion, $precio, $categoria, $stock, $imagen);
    }
    $stmt->execute();
    echo json_encode(['success' => true]);

} elseif ($metodo === 'DELETE') {
    // Eliminar producto
    parse_str(file_get_contents("php://input"), $_DELETE);
    $id = $_DELETE['id'];

    $stmt = $conexion->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
}

$conexion->close();
?>
