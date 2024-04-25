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
// Configuración de la conexión a la base de datos
$server = "localhost"; // Servidor de la base de datos (puede variar según tu configuración)
$user = "root"; // Usuario de la base de datos (puede variar según tu configuración)
$pass = ""; // Contraseña de la base de datos (puede variar según tu configuración)
$db = "datosgenerales"; // Nombre de la base de datos que contiene la tabla 'articulos'

// Recibir los datos del cliente
$data = json_decode(file_get_contents('php://input'), true);

// Conexión a la base de datos
$conn = new mysqli($server, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("LA CONEXIÓN FALLÓ: " . $conn->connect_error);
}

// Preparar la consulta para insertar datos
$sql = "INSERT INTO articulos (codigo, articulo, grupo, peso, valorRepo, tipo, estado) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Verificar si la consulta se preparó correctamente
if (!$stmt) {
    die("ERROR AL PREPARAR LA CONSULTA: " . $conn->error);
}

// Bucle para insertar cada conjunto de datos
foreach ($data as $row) {
    // Bindear los parámetros
    $stmt->bind_param("ssssssss", $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6]);

    // Ejecutar la consulta
    if (!$stmt->execute()) {
        die("ERROR AL INSERTAR DATOS: " . $stmt->error);
    }
}

// Cerrar la consulta
$stmt->close();

// Cerrar la conexión a la base de datos
$conn->close();

// Envía una respuesta al cliente
http_response_code(200); // Respuesta de éxito
?>
