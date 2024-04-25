<?php
// Conexión a la base de datos (reemplaza los valores con los de tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datosgenerales";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("CONEXIÓN FALLIDA: " . $conn->connect_error);
}

// Obtener la sede seleccionada desde la solicitud AJAX
$sede = $_GET['sede'];

// Consulta para obtener el dato de per_autorizado correspondiente a la sede seleccionada
$sql = "SELECT per_autorizado FROM sedes WHERE sede = '$sede'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Obtener el valor de per_autorizado
    $row = $result->fetch_assoc();
    $per_autorizado = $row["per_autorizado"];

    // Devolver el valor en formato JSON
    echo json_encode($per_autorizado);
} else {
    // Devolver un mensaje de error si no se encontraron resultados
    echo json_encode("No se encontraron datos para la sede seleccionada");
}

// Cerrar la conexión
$conn->close();
?>
