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
// Datos de conexión a la base de datos
$server = "localhost";
$user = "root";
$pass = "";
$db = "datosgenerales";

// Obtener la cédula enviada por AJAX
$cedula = strtoupper($_GET['cedula']);

// Conexión a la base de datos
$conn = new mysqli($server, $user, $pass, $db);

// Verificar si se ha establecido la conexión correctamente
if ($conn->connect_error) {
    die("ERROR DE CONEXIÓN: " . $conn->connect_error);
}

// Consulta para verificar si la cédula ya existe en la base de datos
$sql_verificar = "SELECT nombre FROM empleados WHERE cedula = ?";
$stmt_verificar = $conn->prepare($sql_verificar);
$stmt_verificar->bind_param("s", $cedula);
$stmt_verificar->execute();
$stmt_verificar->store_result();

// Si existe un empleado con la misma cédula
if ($stmt_verificar->num_rows > 0) {
    // Obtener el nombre del empleado existente
    $stmt_verificar->bind_result($nombre_existente);
    $stmt_verificar->fetch();
    
    // Mostrar alerta con el nombre del empleado existente
    echo "alert('ESTA CÉDULA YA TIENE UN REGISTRO EXISTENTE, ESTÁ ASIGNADA A \"$nombre_existente\"')";
} else {
    // Si no existe un empleado con la misma cédula, retornar mensaje vacío
    echo "";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
