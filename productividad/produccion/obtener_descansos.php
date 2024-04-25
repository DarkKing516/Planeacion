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

// Obtener la sede seleccionada
$sede = $_POST['sede'];

// Consulta para obtener los descansos de la sede seleccionada
$sql_descansos = "SELECT cod_descanso, nom_descanso, tiempo_des FROM descansos WHERE sede = '$sede'";
$result_descansos = $conn->query($sql_descansos);

$descansos = array();

if ($result_descansos->num_rows > 0) {
    while($row = $result_descansos->fetch_assoc()) {
        // Agregar descansos al array
        $descansos[] = $row;
    }
}

// Cerrar la conexión
$conn->close();

// Devolver los descansos en formato JSON
echo json_encode($descansos);
?>
