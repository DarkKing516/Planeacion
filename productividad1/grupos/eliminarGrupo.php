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
// Verificar si se recibió el ID del grupo a eliminar
if(isset($_POST['id'])) {
    $idGrupo = $_POST['id'];

    // Aquí va tu código para conectarte a la base de datos y ejecutar la consulta de eliminación
    // Conectarse a la base de datos
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "datosgenerales";

    $conn = new mysqli($server, $user, $pass, $db);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("ERROR DE CONEXION: " . $conn->connect_error);
    }

    // Preparar la consulta SQL para eliminar el grupo
    $sql = "DELETE FROM grupos WHERE codigo = $idGrupo";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        echo "EL GRUPO SE HA ELIMINADO CORRECTAMENTE.";
    } else {
        echo "ERROR AL ELIMINAR EL GRUPO: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "NO SE RECIBIÓ EL ID DEL GRUPO A ELIMINAR.";
}
?>
