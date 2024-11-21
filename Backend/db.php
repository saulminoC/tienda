<?php
$servername = "localhost"; // Servidor de la base de datos
$username = "root";        // Usuario de la base de datos (ajusta si es necesario)
$password = "";            // Contraseña (ajusta si es necesario)
$dbname = "tienda";        // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);


// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
