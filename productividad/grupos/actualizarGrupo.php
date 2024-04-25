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
if(isset($_POST['codigo']) && isset($_POST['nombre'])) {
    // Obtener los datos del formulario
    $codigoGrupo = $_POST['codigo'];
    $nombreGrupo = $_POST['nombre'];

    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "datosgenerales";

    // Crear conexión a la base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Verificar la conexión
    if ($conn->connect_error) {
        die("CONEXIÓN FALLIDA: " . $conn->connect_error);
    }

    // Preparar la consulta SQL para actualizar el grupo
    $sql = "UPDATE grupos SET nombre='$nombreGrupo' WHERE codigo=$codigoGrupo";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        // Mostrar un mensaje de éxito
        echo "<script>alert('EL GRUPO SE HA ACTUALIZADO CORRECTAMENTE.');</script>";
    } else {
        // Mostrar un mensaje de error
        echo "<script>alert('ERROR AL ACTUALIZAR EL GRUPO: " . $conn->error . "');</script>";
    }

    // Cerrar la conexión
    $conn->close();
} else {
    // Si no se recibieron los datos del formulario, mostrar un mensaje de error
    echo "<script>alert('NO SE RECIBIERON LOS DATOS NECESARIOS PARA ACTUALIZAR EL GRUPO.');</script>";
}

// Redirigir de nuevo a grupos.php
echo "<script>window.location.href = 'grupos.php';</script>";
?>
