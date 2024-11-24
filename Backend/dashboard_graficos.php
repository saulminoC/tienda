<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "tienda");
if ($conn->connect_error) {
    echo json_encode(['error' => 'Error de conexión a la base de datos.']);
    exit;
}

// Ventas mensuales
$sqlVentasMensuales = "SELECT MONTH(fecha) AS mes, SUM(total) AS total FROM pedidos GROUP BY mes ORDER BY mes";
$resultVentasMensuales = $conn->query($sqlVentasMensuales);
$ventasMensuales = ['labels' => [], 'data' => []];
while ($row = $resultVentasMensuales->fetch_assoc()) {
    $ventasMensuales['labels'][] = $row['mes'];
    $ventasMensuales['data'][] = $row['total'];
}

// Productos más vendidos (actualizado con JOIN)
$sqlProductosMasVendidos = "
    SELECT p.nombre, SUM(v.cantidad) AS total
    FROM productos_vendidos v
    JOIN productos p ON v.producto_id = p.id
    GROUP BY p.nombre
    ORDER BY total DESC
    LIMIT 5
";
$resultProductos = $conn->query($sqlProductosMasVendidos);
$productosMasVendidos = ['labels' => [], 'data' => []];
while ($row = $resultProductos->fetch_assoc()) {
    $productosMasVendidos['labels'][] = $row['nombre'];
    $productosMasVendidos['data'][] = $row['total'];
}

// Respuesta
echo json_encode([
    'ventasMensuales' => $ventasMensuales,
    'productosMasVendidos' => $productosMasVendidos
]);
$conn->close();
?>
