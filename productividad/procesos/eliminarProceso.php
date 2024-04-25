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
// Verificar si se ha enviado el formulario de eliminación
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "datosgenerales";

    // Conexión a la base de datos
    $conn = new mysqli($server, $user, $pass, $db);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("ERROR DE CONEXIÓN: " . $conn->connect_error);
    }

    // Obtener el ID del registro a eliminar
    $eliminar_id = $_GET['id'];

    // Preparar la consulta SQL para eliminar el registro
    $eliminar_sql = "DELETE FROM procesos WHERE id = ?";

    // Preparar y ejecutar la consulta de eliminación usando una consulta preparada
    $stmt = $conn->prepare($eliminar_sql);
    $stmt->bind_param("i", $eliminar_id);
    if ($stmt->execute()) {
        // Mostrar la alerta y redirigir
        echo "<script>alert('REGISTRO ELIMINADO CORRECTAMENTE'); window.location.href = 'procesos.php';</script>";
    } else {
        // Mostrar mensaje de error
        echo "ERROR AL ELIMINAR EL REGISTRO: " . $conn->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    // Si no se recibió el ID del proceso a eliminar, mostrar mensaje de error
    echo "NO SE RECIBIÓ EL ID DEL PROCESO A ELIMINAR.";
}
?>
