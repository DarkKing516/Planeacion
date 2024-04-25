<?php
// Iniciar sesión
session_start();

// Verificar si no hay una sesión activa para el usuario
if (!isset($_SESSION['usuario'])) {
    // Mostrar mensaje de alerta
    echo "<script>alert('DEBES INICIAR SESIÓN PARA PODER INGRESAR A ESTA PÁGINA');</script>";
    
    // Redirigir al usuario de vuelta a la página de inicio de sesión
    header("Location: /productividad/index.php");
    exit(); // Asegurar que el script se detenga después de redirigir
}
?>

<?php
// Establecer la conexión con la base de datos
$server = "localhost";
$user = "root";
$pass = "";
$db = "datosgenerales";
$conn = new mysqli($server, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("LA CONEXIÓN FALLÓ: " . $conn->connect_error);
}

// Obtener los datos enviados desde el frontend
$data = json_decode(file_get_contents("php://input"), true);

// Preparar la consulta para insertar datos
$sql = "INSERT INTO empleados (estado, cedula, nombre, cargo, sede, fecha, basico, vHora) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Verificar si la consulta se preparó correctamente
if ($stmt === false) {
    die("ERROR AL PREPARAR LA CONSULTA: " . $conn->error);
}

// Recorrer los datos y ejecutar la consulta para cada conjunto de datos
foreach ($data as $row) {
    // Eliminar caracteres no numéricos y convertir a decimal
    $basico = str_replace(array("$", ".", ","), "", $row[6]); // Ahora debe ser [7] para 'basico'
    $basico = floatval($basico);

    $vHora = str_replace(array("$", ".", ","), "", $row[7]); // Ahora debe ser [6] para 'vHora'
    $vHora = floatval($vHora);

    // Enlazar parámetros y ejecutar la consulta
    $stmt->bind_param("ssssssdd", $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $basico, $vHora);
    if (!$stmt->execute()) {
        die("ERROR AL INSERTAR DATOS: " . $stmt->error);
    }
}

// Cerrar la consulta y la conexión a la base de datos
$stmt->close();
$conn->close();

// Enviar una respuesta al frontend
http_response_code(200);
echo json_encode(array("message" => "LOS DATOS SE HAN GUARDADO CORRECTAMENTE."));
?>
