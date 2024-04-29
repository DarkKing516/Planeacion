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
// Verificar si se recibió el id y el nuevo nombre del tipo
if(isset($_POST['id']) && isset($_POST['nombre'])) {
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

    // Preparar la consulta SQL para actualizar el nombre del tipo
    $idTipo = $_POST['id']; // Cambio de $idRol a $idTipo
    $nuevoNombre = $_POST['nombre'];
    $sql = "UPDATE tipos SET nombre = '$nuevoNombre' WHERE id = $idTipo";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        echo "EL NOMBRE DEL TIPO SE HA ACTUALIZADO CORRECTAMENTE.";
    } else {
        echo "ERROR AL ACTUALIZAR EL NOMBRE DEL TIPO: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "NO SE RECIBIÓ EL ID Y EL NUEVO NOMBRE DEL TIPO.";
}
?>
