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

// Obtener el proceso seleccionado desde la solicitud GET
$procesoSeleccionado = $_GET['proceso'];

// Consulta SQL para obtener los artículos correspondientes al proceso seleccionado
$sql_articulos_por_proceso = "SELECT articulo FROM articulos WHERE proceso = '$procesoSeleccionado'";
$result_articulos_por_proceso = $conn->query($sql_articulos_por_proceso);

// Crear opciones HTML para los artículos
$options_articulos_por_proceso = '';
if ($result_articulos_por_proceso->num_rows > 0) {
    while ($row = $result_articulos_por_proceso->fetch_assoc()) {
        $options_articulos_por_proceso .= "<option value='{$row['articulo']}'>{$row['articulo']}</option>";
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver las opciones de los artículos como respuesta
echo $options_articulos_por_proceso;
?>
