<?php
// Conexión a la base de datos (reemplaza los valores con los de tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datosgenerales";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el código del artículo y el nombre estándar enviados por POST
$cod_articulo = $_POST['cod_articulo'];
$nombre_estandar = $_POST['nombre_estandar'];

// Consulta para obtener el tiempo estándar asociado al nombre estándar y al artículo
$sql = "SELECT t_estandar FROM estandar WHERE cod_articulo = '$cod_articulo' AND nombre_estandar = '$nombre_estandar'";
$result = $conn->query($sql);

// Verificar si se encontró el tiempo estándar
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $t_estandar = $row['t_estandar'];
    // Devolver el tiempo estándar en formato JSON
    echo json_encode($t_estandar);
} else {
    // Devolver un mensaje de error si no se encuentra el tiempo estándar
    echo json_encode("Tiempo estándar no encontrado");
}

// Cerrar la conexión
$conn->close();
?>