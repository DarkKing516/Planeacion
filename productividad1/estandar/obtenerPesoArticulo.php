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

// Obtener el código del artículo enviado por la solicitud AJAX
$articuloCodigo = $_GET['codigo'];

// Consulta para obtener el peso del artículo
$sql = "SELECT peso FROM articulos WHERE codigo = '$articuloCodigo'";
$resultado = $conn->query($sql);

// Verificar si se encontró el artículo
if ($resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    $peso = $row['peso'];
    echo $peso; // Devolver el peso como respuesta a la solicitud AJAX
} else {
    echo "0"; // En caso de que no se encuentre el artículo
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
