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
// Verificar si los parámetros 'articulo' y 'proceso' se reciben correctamente
if(isset($_GET['articulo']) && isset($_GET['proceso'])) {
    $articulo = $_GET['articulo'];
    $proceso = $_GET['proceso'];

    // Conexión a la base de datos
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "datosgenerales";

    $conn = new mysqli($server, $user, $pass, $db);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("ERROR DE CONEXIÓN: " . $conn->connect_error);
    }

    // Consulta SQL para obtener el estándar
    $sql_estandar = "SELECT estandar FROM articulos WHERE articulo = '$articulo' AND proceso = '$proceso'";
    $result_estandar = $conn->query($sql_estandar);

    if ($result_estandar->num_rows > 0) {
        // Si se encontró el estándar, enviarlo como respuesta
        $row = $result_estandar->fetch_assoc();
        echo $row['estandar'];
    } else {
        // Si no se encontró el estándar, enviar un mensaje de error
        echo "No se encontró el estándar para el artículo y proceso especificados.";
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    // Si los parámetros no se proporcionaron correctamente, enviar un mensaje de error
    echo "No se proporcionaron los parámetros 'articulo' y 'proceso'.";
}
?>
