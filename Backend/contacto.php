<?php
header('Content-Type: application/json');

// Validar datos del formulario
$nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$mensaje = filter_input(INPUT_POST, 'mensaje', FILTER_SANITIZE_STRING);

if (!$nombre || !$email || !$mensaje) {
    echo json_encode(['exito' => false, 'mensaje' => 'Datos inválidos.']);
    exit;
}

// Configurar envío de correo (o almacenar en la base de datos)
$destinatario = "saulcartel12@gmail.com";
$asunto = "Nuevo Mensaje de Contacto";
$cuerpo = "Nombre: $nombre\nCorreo: $email\nMensaje:\n$mensaje";

$headers = "From: $email\r\nReply-To: $email";

if (mail($destinatario, $asunto, $cuerpo, $headers)) {
    echo json_encode(['exito' => true, 'mensaje' => 'Mensaje enviado con éxito.']);
} else {
    echo json_encode(['exito' => false, 'mensaje' => 'No se pudo enviar el mensaje.']);
}
