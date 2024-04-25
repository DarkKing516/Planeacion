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
// Verificar si se recibieron los datos del formulario
if(isset($_POST['id']) && isset($_POST['sede']) && isset($_POST['per_autorizado'])) {
    // Obtener los datos del formulario
    $idSede = $_POST['id'];
    $nuevaSede = $_POST['sede'];
    $nuevoPerAutorizado = $_POST['per_autorizado'];

    // Conexión a la base de datos
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "datosgenerales";

    // Crear conexión a la base de datos
    $conn = new mysqli($server, $user, $pass, $db);
    // Verificar la conexión
    if ($conn->connect_error) {
        die("CONEXIÓN FALLIDA: " . $conn->connect_error);
    }

    // Preparar la consulta SQL para actualizar la sede con el nuevo nombre y el número de personal autorizado
    $sql = "UPDATE sedes SET sede='$nuevaSede', per_autorizado='$nuevoPerAutorizado' WHERE id=$idSede";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        $response = "LA SEDE SE HA ACTUALIZADO CORRECTAMENTE.";
    } else {
        $response = "ERROR AL ACTUALIZAR LA SEDE: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();

    // Devolver respuesta
    echo $response;
} else {
    echo "NO SE RECIBIERON LOS DATOS NECESARIOS PARA ACTUALIZAR LA SEDE.";
}
?>
