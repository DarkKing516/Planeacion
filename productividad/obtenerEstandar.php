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
// Establecer la conexión a la base de datos MySQL
$server = "localhost";
$user = "root";
$pass = "";
$db = "datosgenerales";

// Crear la conexión
$conn = new mysqli($server, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("ERROR DE CONEXIÓN: " . $conn->connect_error);
}

// Obtener los valores de proceso y artículo enviados por la solicitud AJAX
$proceso = $_POST['proceso'];
$articulo = $_POST['articulo'];

// Consulta SQL para obtener el estándar basado en el proceso y el artículo seleccionados
$sql = "SELECT estandar FROM articulos WHERE proceso = '$proceso' AND articulo = '$articulo'";
$result = $conn->query($sql);

// Verificar si se encontraron resultados
if ($result) {
    // Verificar si se encontró exactamente un resultado
    if ($result->num_rows === 1) {
        // Obtener el resultado y devolverlo en formato JSON
        $row = $result->fetch_assoc();
        echo json_encode(array("estandar" => $row['estandar']));
    } else {
        // Si no se encontró exactamente un resultado, devolver un mensaje de error
        echo json_encode(array("error" => "NO SE ENCONTRÓ UN ÚNICO ESTÁNDAR PARA EL ARTÍCULO Y PROCESO SELECCIONADOS"));
    }
} else {
    // Si hubo un error en la consulta SQL, devolver un mensaje de error en formato JSON
    echo json_encode(array("error" => "ERROR EN LA CONSULTA SQL: " . $conn->error));
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
