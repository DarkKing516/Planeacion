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
// Verificar si se recibió el parámetro 'id'
if(isset($_POST['id'])) {
    // Obtener el ID del descanso a eliminar
    $id_descanso = $_POST['id'];

    // Establecer la conexión a la base de datos (Asegúrate de incluir tus credenciales correctas)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "datosgenerales";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("CONEXIÓN FALLIDA: " . $conn->connect_error);
    }

    // Preparar la consulta para eliminar el descanso
    $sql = "DELETE FROM descansos WHERE cod_descanso = $id_descanso";

    // Ejecutar la consulta y verificar si se realizó correctamente
    if ($conn->query($sql) === TRUE) {
        echo "EL DESCANSO SE HA ELIMINADO CORRECTAMENTE";
    } else {
        echo "ERROR AL ELIMINAR EL DESCANSO: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    // Si no se recibió el parámetro 'id', mostrar un mensaje de error
    echo "ERROR: EL PARÁMETRO ID NO ESTÁ DEFINIDO.";
}
?>
