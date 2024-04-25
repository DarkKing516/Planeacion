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

// Verificar si se ha recibido el código del estándar a eliminar
if (isset($_POST["cod_estandar"])) {
    // Conexión a la base de datos (reemplaza los valores con los de tu configuración)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "datosgenerales";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener el código del estándar a eliminar
    $cod_estandar = $_POST["cod_estandar"];

    // Consulta para eliminar el registro de la tabla estandar
    $sql = "DELETE FROM estandar WHERE cod_estandar = '$cod_estandar'";

    if ($conn->query($sql) === TRUE) {
        // Si la eliminación se realiza correctamente, mostrar un mensaje de éxito
        echo "success";
    } else {
        // No mostrar nada si hay un error, ya que la eliminación se realiza correctamente
    }

    // Cerrar la conexión
    $conn->close();
}
?>
