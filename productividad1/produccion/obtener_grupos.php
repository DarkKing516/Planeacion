<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datosgenerales";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener los grupos
$sql = "SELECT DISTINCT cod_grupo, grupo FROM estandar";
$result = $conn->query($sql);

// Array para almacenar los datos
$grupos = array();

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Recorrer los resultados y guardarlos en el array
    while($row = $result->fetch_assoc()) {
        // Agregar un nuevo objeto al array
        $grupos[] = array(
            'cod_grupo' => $row['cod_grupo'],
            'grupo' => $row['grupo']
        );
    }
}

// Cerrar la conexión
$conn->close();

// Devolver los datos como JSON
header('Content-Type: application/json');
echo json_encode($grupos);
?>
