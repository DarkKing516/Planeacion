<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datosgenerales";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener los datos del formulario principal
$nombre = $_POST['nombre'];
$fecha = $_POST['fecha'];
$sexo = $_POST['sexo'];
$correo = $_POST['correo'];
$direccion = $_POST['direccion'];

// Procesar los campos de teléfono, estudio y actividad
$telefonos = $_POST['telefono'];
$estudios = $_POST['estudio'];
$actividades = $_POST['actividad'];

// Combinar los datos de los campos en un solo conjunto de datos
$datos = array();
for ($i = 0; $i < count($telefonos); $i++) {
    $telefono = $telefonos[$i];
    $estudio = $estudios[$i];
    $actividad = $actividades[$i];
    $datos[] = array('telefono' => $telefono, 'estudio' => $estudio, 'actividad' => $actividad);
}

// Insertar datos en la tabla para cada conjunto de datos
foreach ($datos as $dato) {
    $telefono = $dato['telefono'];
    $estudio = $dato['estudio'];
    $actividad = $dato['actividad'];

    $sql = "INSERT INTO ejemplo (nombre, fecha, sexo, correo, direccion, telefono, estudio, actividad) 
            VALUES ('$nombre', '$fecha', '$sexo', '$correo', '$direccion', '$telefono', '$estudio', '$actividad')";

    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Redirigir al usuario de vuelta a ejemplo.php con el parámetro guardado en la URL
header("Location: ejemplo.php?guardado=true");
exit;
?>
