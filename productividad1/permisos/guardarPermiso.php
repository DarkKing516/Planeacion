<?php
// Establecer la conexión a la base de datos MySQL
$server = "localhost";
$user = "root";
$pass = "";
$db = "datosgenerales";

// Crear la conexión
$conn = new mysqli($server, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("ERROR DE CONEXIÓN: " . $conn->connect_error);
}

// Procesar los datos del formulario si se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $principal = $_POST["principales"];
    $nodo = $_POST["nodo"];
    $modulo = $_POST["modulo"];
    $rol = $_POST["rol"];
    
    // Crear la consulta SQL para insertar los datos en la tabla permisos
    $sql = "INSERT INTO permisos (principales, nodo, modulo, rol) VALUES ('$principal', '$nodo', '$modulo', '$rol')";

   
}
?>
