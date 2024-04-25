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
// Conexión a la base de datos
$server = "localhost";
$user = "root";
$pass = "";
$db = "datosgenerales";

$conn = new mysqli($server, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("ERROR DE CONEXION: " . $conn->connect_error);
}

// Consulta SQL para obtener los roles
$sql = "SELECT id, nombre FROM roles"; // Incluimos el campo 'id' en la consulta
$result = $conn->query($sql);

$roles = array();

if ($result->num_rows > 0) {
    // Recuperar los datos de los roles y agregarlos al array
    while ($row = $result->fetch_assoc()) {
        $roles[] = $row;
    }
}

// Cerrar la conexión
$conn->close();

// Devolver los roles como JSON
header('Content-Type: application/json');
echo json_encode($roles);
?>
