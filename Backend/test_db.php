<?php
$servername = "sql211.infinityfree.com";
$username = "if0_37760091";
$password = "mAmq9OUQ1gVZ";
$dbname = "if0_37760091_mi_base";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
echo "Conexión exitosa a la base de datos.";
$conn->close();
?>
