<?php
// Establecer la conexión a la base de datos MySQL
$server = "localhost";
$user = "root";
$pass = "";
$db = "datosgenerales";

$conn = new mysqli($server, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("ERROR DE CONEXIÓN: " . $conn->connect_error);
}

// Consulta SQL para obtener los registros de la tabla 'sedes'
$sql_sede = "SELECT DISTINCT sede FROM sedes";
$resultado_sede = $conn->query($sql_sede);
$optionsSede = '';
if ($resultado_sede->num_rows > 0) {
    while ($row = $resultado_sede->fetch_assoc()) {
        $optionsSede .= "<option value='{$row['sede']}'>{$row['sede']}</option>";
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver las opciones de selección de sedes
echo $optionsSede;
?>
