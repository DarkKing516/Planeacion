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

// Obtener la sede seleccionada desde la solicitud AJAX
$sede = $_POST['sede'];

// Consulta para obtener los empleados de la sede seleccionada
$sql_empleados = "SELECT cedula, nombre FROM empleados WHERE sede = '$sede'";
$result_empleados = $conn->query($sql_empleados);

// Array para almacenar los nombres y cédulas de los empleados
$empleados = array();

// Guardar los nombres y cédulas de los empleados en el array
while($row = $result_empleados->fetch_assoc()) {
    $empleados[] = $row;
}

// Consulta SQL para obtener los tiempos de descanso desde la base de datos y convertirlos a minutos
$sql_descansos = "SELECT ROUND(IFNULL(TIME_TO_SEC(tiempo_des) / 60, 0)) AS tiempo_en_minutos, nom_descanso FROM descansos WHERE sede = '$sede'";
$result_descansos = $conn->query($sql_descansos);

// Array para almacenar los tiempos de descanso
$tiempos = array();

// Guardar los tiempos de descanso en el array
while($row = $result_descansos->fetch_assoc()) {
    $tiempos[$row['nom_descanso']] = $row['tiempo_en_minutos'];
}

// Devolver los nombres y cédulas de los empleados y los tiempos de descanso como JSON
$response = array(
    'empleados' => $empleados,
    'tiempos' => $tiempos
);

echo json_encode($response);

// Cerrar la conexión
$conn->close();
?>
