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
$sql = "SELECT nombre FROM grupos";
$result = $conn->query($sql);

// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    // Array para almacenar los grupos
    $grupos = array();

    // Obtener todos los resultados de la consulta y almacenarlos en una matriz
    $rows = $result->fetch_all(MYSQLI_ASSOC);

    // Iterar sobre la matriz de resultados y almacenar los grupos en el array
    for ($i = 0; $i < count($rows); $i++) {
        $grupos[] = $rows[$i]["nombre"];
    }

    // Convertir el array de grupos a formato JSON y devolverlo
    echo json_encode($grupos);
    echo $grupos[1];
} else {
    // No se encontraron resultados
    echo json_encode(array());
}

// Cerrar la conexión
$conn->close();
