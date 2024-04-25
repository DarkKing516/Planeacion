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

// Configuración de la conexión a la base de datos
$server = "localhost";
$user = "root";
$pass = "";
$db = "datosgenerales";

// Conexión a la base de datos
$conn = new mysqli($server, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("LA CONEXIÓN FALLÓ: " . $conn->connect_error);
}

// Obtener los datos enviados por AJAX
$data = json_decode(file_get_contents("php://input"), true);

// Preparar la consulta para insertar datos
$sql = "INSERT INTO estandar (cod_grupo, grupo, cod_proceso, proceso, cod_articulo, articulo, peso, cod_estandar, nombre_estandar, t_estandar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Verificar si la consulta se preparó correctamente
if (!$stmt) {
    die("ERROR AL PREPARAR LA CONSULTA: " . $conn->error);
}

// Bucle para insertar los datos
foreach ($data as $row) {
    $stmt->bind_param("ssssssssss", $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9]);
    
    // Ejecutar la consulta
    if (!$stmt->execute()) {
        die("ERROR AL INSERTAR DATOS: " . $stmt->error);
    }
}

// Cerrar la consulta
$stmt->close();

// Cerrar la conexión a la base de datos
$conn->close();

// Enviar respuesta al cliente
http_response_code(200); // Código de respuesta HTTP OK
echo "DATOS GUARDADOS CORRECTAMENTE";
?>
