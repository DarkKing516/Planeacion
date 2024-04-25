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
// Establecer la conexión a la base de datos MySQL
$server = "localhost";
$user = "root";
$pass = "";
$db = "datosgenerales";

// Crear la conexión
$conn = new mysqli($server, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("ERROR DE CONEXIÓN: " . $conn->connect_error);
}

// Verificar si se ha enviado el nombre
if(isset($_POST['nombre'])) {
    $nombre = $_POST['nombre'];

    // Obtener la cédula del empleado
    $sql_cedula = "SELECT cedula FROM empleados WHERE nombre = ?";
    $stmt = $conn->prepare($sql_cedula);
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $result_cedula = $stmt->get_result();

    // Verificar si se encontró la cédula
    if ($result_cedula->num_rows > 0) {
        $row = $result_cedula->fetch_assoc();
        // Devolver la cédula en formato JSON sin comillas
        echo $row['cedula'];
    } else {
        // Devolver un mensaje de error si no se encontró la cédula
        echo "Cédula no encontrada";
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
