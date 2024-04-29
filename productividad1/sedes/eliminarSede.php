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
if(isset($_POST['id'])) {
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

    // Obtener el ID del rol a eliminar
    $idSede = $_POST['id']; // Corregimos la variable de ID aquí

    // Preparar la consulta SQL para eliminar el rol
    $sql = "DELETE FROM sedes WHERE id = $idSede"; // Corregimos el nombre de la variable aquí

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        echo "LA SEDE SE HA ELIMINADO CORRECTAMENTE.";
    } else {
        echo "ERROR AL ELIMINAR LA SEDE: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "NO SE RECIBIÓ EL ID DE LA SEDE A ELIMINAR.";
}
?>
