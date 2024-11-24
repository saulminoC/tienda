<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['editar_producto'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $categoria = $_POST['categoria'];
        $imagen = $_POST['imagen'];
        $stock = $_POST['stock'];

        // Actualizar producto en la base de datos
        $sql = "UPDATE productos SET 
                    nombre = ?, 
                    descripcion = ?, 
                    precio = ?, 
                    categoria = ?, 
                    imagen = ?, 
                    stock = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssdssii', $nombre, $descripcion, $precio, $categoria, $imagen, $stock, $id);

        if ($stmt->execute()) {
            echo "Producto actualizado correctamente.";
        } else {
            echo "Error al actualizar el producto: " . $conn->error;
        }
    }
}

// Obtener todos los productos
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);
$productos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
}
?>
