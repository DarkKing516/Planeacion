<?php
// Conexión a la base de datos (reemplaza los valores con los de tu configuración)
$server = "localhost";
$user = "root";
$pass = "";
$db = "datosgenerales";

$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) {
    die("ERROR DE CONEXIÓN: " . $conn->connect_error);
}

// Obtener la sede enviada por AJAX
$sede = $_POST['sede'];

// Consulta SQL para obtener los tiempos de descanso desde la base de datos por nombre
$sql = "SELECT tiempo FROM Descansos2 WHERE sede = '$sede' AND (nombre = 'descanso1' OR nombre = 'descanso2' OR nombre = 'almuerzo')";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        $tiempos = array();
        while ($row = $result->fetch_assoc()) {
            $tiempos[] = $row["tiempo"];
        }
        echo implode(",", $tiempos);
    } else {
        echo "Error: No se encontraron datos para la sede seleccionada";
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
