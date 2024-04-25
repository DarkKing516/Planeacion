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
// Verificar si se recibió un ID válido del salario a eliminar
if(isset($_GET['id']) && !empty($_GET['id'])) {
    // Recibir el ID del salario a eliminar
    $id = $_GET['id'];

    // Conexión a la base de datos (ajusta los valores según tu configuración)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "datosgenerales";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("CONEXIÓN FALLIDA: " . $conn->connect_error);
    }

    // Preparar la consulta SQL para eliminar el salario con el ID proporcionado
    $sql = "DELETE FROM salarios WHERE id = $id";

    // Ejecutar la consulta SQL
    if ($conn->query($sql) === TRUE) {
        // Si la eliminación es exitosa, responder con un código 200 (OK)
        http_response_code(200);
        echo json_encode(array("message" => "SALARIO ELIMINADO CORRECTAMENTE."));
    } else {
        // Si hay un error al eliminar, responder con un código 500 (Error interno del servidor)
        http_response_code(500);
        echo json_encode(array("message" => "ERROR AL ELIMINAR EL SALARIO: " . $conn->error));
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    // Si no se recibió un ID válido, responder con un código 400 (Solicitud incorrecta)
    http_response_code(400);
    echo json_encode(array("message" => "ID DE SALARIO NO VÁLIDO."));
}
?>
