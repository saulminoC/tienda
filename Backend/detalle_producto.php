<?php
// Configuración de la cabecera para devolver JSON
header('Content-Type: application/json');

// Conexión a la base de datos
require 'db.php';

// Verificar si el parámetro "id" está presente en la URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convertir a entero para evitar inyecciones SQL

    // Consulta preparada para obtener los detalles del producto
    $sql = "SELECT id, nombre, descripcion, precio, imagen FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si se encontró el producto
        if ($result->num_rows > 0) {
            $producto = $result->fetch_assoc();
            echo json_encode($producto); // Devolver el producto en formato JSON
        } else {
            // Si no se encontró el producto, devolver un error
            echo json_encode([
                "error" => "Producto no encontrado"
            ]);
        }

        $stmt->close();
    } else {
        // Error en la preparación de la consulta
        echo json_encode([
            "error" => "Error en la consulta SQL"
        ]);
    }
} else {
    // Si no se proporciona un ID en la URL
    echo json_encode([
        "error" => "ID de producto no proporcionado"
    ]);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
