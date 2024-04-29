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
// Verificar si se recibió el id del rol
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

    // Preparar la consulta SQL para obtener el nombre del rol
    $idRol = $_POST['id'];
    $sql = "SELECT nombre FROM tipos WHERE id = $idRol";

    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Verificar si se encontró el rol
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nombreRol = $row["nombre"];
        echo $nombreRol; // Devolver el nombre del rol como respuesta
    } else {
        echo "NO SE ENCONTRÓ EL NOMBRE DEL TIPO.";
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "NO SE RECIBIÓ EL ID DEL TIPO.";
}
?>
