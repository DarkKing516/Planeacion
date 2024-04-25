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
// Verificar si se recibió el nombre del nuevo rol
if(isset($_POST['nombre'])) {
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

    // Preparar la consulta SQL para insertar el nuevo rol
    $nombreRol = $_POST['nombre'];
    $sql = "INSERT INTO roles (nombre) VALUES ('$nombreRol')";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        $response = array("success" => true, "message" => "EL ROL SE HA GUARDADO CORRECTAMENTE.");
    } else {
        $response = array("success" => false, "message" => "ERROR AL GUARDAR EL ROL: " . $conn->error);
    }

    // Cerrar la conexión
    $conn->close();

    // Devolver respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    echo "NO SE RECIBIÓ EL NOMBRE DEL ROL.";
}
?>
