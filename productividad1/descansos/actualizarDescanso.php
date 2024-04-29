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
if(isset($_POST['cod_descanso']) && isset($_POST['nom_descanso']) && isset($_POST['sede']) && isset($_POST['ini_descanso']) && isset($_POST['fin_descanso']) && isset($_POST['tiempo_des'])) {
    // Obtener los datos del formulario
    $cod_descanso = $_POST['cod_descanso'];
    $nom_descanso = $_POST['nom_descanso'];
    $sede = $_POST['sede'];
    $ini_descanso = $_POST['ini_descanso'];
    $fin_descanso = $_POST['fin_descanso'];
    $tiempo_des = $_POST['tiempo_des'];

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

    // Preparar la consulta SQL para actualizar el descanso
    $sql = "UPDATE descansos SET nom_descanso='$nom_descanso', sede='$sede', ini_descanso='$ini_descanso', fin_descanso='$fin_descanso', tiempo_des='$tiempo_des' WHERE cod_descanso=$cod_descanso";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        // Mostrar un mensaje de éxito
        echo "<script>alert('EL DESCANSO SE HA ACTUALIZADO CORRECTAMENTE.');</script>";
    } else {
        // Mostrar un mensaje de error
        echo "<script>alert('ERROR AL ACTUALIZAR EL DESCANSO: " . $conn->error . "');</script>";
    }

    // Cerrar la conexión
    $conn->close();
} else {
    // Si no se recibieron los datos del formulario, mostrar un mensaje de error
    echo "<script>alert('NO SE RECIBIERON LOS DATOS NECESARIOS PARA ACTUALIZAR EL DESCANSO.');</script>";
}

// Redirigir de nuevo a descansos.php
echo "<script>window.location.href = 'descansos.php';</script>";
?>
