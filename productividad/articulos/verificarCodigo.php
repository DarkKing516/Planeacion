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
// Configuración de la conexión a la base de datos
$server = "localhost";
$user = "root";
$pass = "";
$db = "datosgenerales";
$conn = new mysqli($server, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("ERROR DE CONEXIÓN: " . $conn->connect_error);
}

// Obtener el código enviado por la solicitud AJAX
$codigo = $_POST['codigo'];

// Verificar si el código ya existe en la base de datos
$codigo_exist_query = "SELECT articulo FROM articulos WHERE codigo = ?";
$stmt_exist = $conn->prepare($codigo_exist_query);
$stmt_exist->bind_param("s", $codigo);
$stmt_exist->execute();
$stmt_exist->store_result();

if ($stmt_exist->num_rows > 0) {
    $stmt_exist->bind_result($articulo_existente);
    $stmt_exist->fetch();
    echo "EL CÓDIGO QUE INGRESASTE YA EXISTE, Y ESTÁ ASIGNADO AL ARTÍCULO \"$articulo_existente\"";
} else {
    echo ""; // No se encontraron coincidencias, devuelve cadena vacía
}

// Cerrar la consulta
$stmt_exist->close();

// Cerrar la conexión a la base de datos
$conn->close();
?>
