<?php
// Establecer conexión a la base de datos
$server = "localhost";
$user = "root";
$pass = "";
$db = "datosgenerales";
$conn = new mysqli($server, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("ERROR DE CONEXIÓN: " . $conn->connect_error);
}

// Obtener la cédula del usuario a eliminar
$cedula = $_POST['cedula'];

// Consulta SQL para eliminar el usuario
$sql = "DELETE FROM usuario WHERE cedula = '$cedula'";

if ($conn->query($sql) === TRUE) {
    echo "exito"; // Enviar respuesta de éxito al cliente
} else {
    echo "ERROR AL ELIMINAR EL USUARIO: " . $conn->error; // Enviar mensaje de error
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
