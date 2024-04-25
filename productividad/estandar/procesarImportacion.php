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
// Verificar que se haya enviado un archivo
if (isset($_FILES['archivo_csv']) && $_FILES['archivo_csv']['error'] == UPLOAD_ERR_OK) {
    $nombre_temporal = $_FILES['archivo_csv']['tmp_name'];
    $nombre_archivo = $_FILES['archivo_csv']['name'];

    // Procesar el archivo CSV aquí y guardar los datos en la base de datos
    
    // Después de guardar los datos, puedes redirigir al usuario de vuelta a importarEstandar.php
    header("Location: importarEstandar.php");
    exit();
} else {
    // Manejar el error si no se envió ningún archivo o ocurrió algún problema durante la carga
}
?>
