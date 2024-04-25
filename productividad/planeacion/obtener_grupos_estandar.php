<?php
// Configurar la conexión a la base de datos (ajusta los valores según tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datosgenerales";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener los grupos desde la tabla estandar
$sql = "SELECT DISTINCT grupo FROM estandar";
$result = $conn->query($sql);

// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    // Array para almacenar los grupos
    $grupos = array();

    // Obtener cada fila de resultados y almacenar los grupos en el array
    while ($row = $result->fetch_assoc()) {
        $grupos[] = $row["grupo"];
    }

    // Convertir el array de grupos a formato JSON y devolverlo
    echo json_encode($grupos);
} else {
    // No se encontraron resultados
    echo json_encode(array());
}

// Cerrar la conexión
$conn->close();
?>
