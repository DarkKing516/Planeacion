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

// Configuración para mostrar errores de PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Datos de conexión a la base de datos
$server = "localhost";
$user = "root";
$pass = "";
$db = "datosgenerales";

// Conexión a la base de datos
$conn = new mysqli($server, $user, $pass, $db);

// Verificar si se ha establecido la conexión correctamente
if ($conn->connect_error) {
    die("ERROR DE CONEXIÓN: " . $conn->connect_error);
}

// Verificar si se han recibido los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $estado = $_POST['estado'];
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $cargo = $_POST['cargo'];
    $sede = $_POST['sede'];
    $fecha = $_POST['fecha'];
    $basico = $_POST['basico'];
    $vHora = $_POST['vHora'];

    // Preparar la consulta para insertar los datos en la tabla 'empleados'
    $sql = "INSERT INTO empleados (estado, cedula, nombre, cargo, sede, fecha, basico, vHora) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Verificar si la consulta se preparó correctamente
    if ($stmt === false) {
        die("ERROR AL PREPARAR LA CONSULTA: " . $conn->error);
    }

    // Vincular los parámetros con los valores de los datos del formulario
    $stmt->bind_param("ssssssss", $estado, $cedula, $nombre, $cargo, $sede, $fecha, $basico, $vHora);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir al usuario de vuelta a la página de creación de empleados
        header("Location: crearEmpleado.php?mensaje=guardado");
        exit();
    } else {
        // Redirigir al usuario de vuelta a la página de creación de empleados con un mensaje de error
        header("Location: crearEmpleado.php?mensaje=error");
        exit();
    }

    // Cerrar la consulta
    $stmt->close();
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
