<?php
header('Content-Type: application/json');

// Datos de conexión

$servername = "localhost"; // Servidor de la base de datos
$username = "root";        // Usuario de la base de datos (ajusta si es necesario)
$password = "";            // Contraseña (ajusta si es necesario)
$dbname = "tienda";        // Nombre de tu base de datos

// Conexión a la base de datos
$conexion = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conexion->connect_error) {
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit;
}

// Obtener parámetros
$categoria = $_GET['categoria'] ?? 'todos';
$precioMin = isset($_GET['precioMin']) ? (float) $_GET['precioMin'] : 0;
$precioMax = isset($_GET['precioMax']) ? (float) $_GET['precioMax'] : PHP_INT_MAX;

// Construir consulta SQL
$sql = "SELECT * FROM productos WHERE precio BETWEEN ? AND ?";

if ($categoria !== 'todos') {
    $sql .= " AND categoria = ?";
}

// Preparar y ejecutar la consulta
if ($stmt = $conexion->prepare($sql)) {
    if ($categoria === 'todos') {
        $stmt->bind_param('dd', $precioMin, $precioMax);
    } else {
        $stmt->bind_param('dds', $precioMin, $precioMax, $categoria);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $productos = [];
    while ($fila = $result->fetch_assoc()) {
        $productos[] = $fila;
    }

    echo json_encode($productos);
    $stmt->close();
} else {
    echo json_encode(['error' => 'Error al preparar la consulta']);
}

$conexion->close();
?>
