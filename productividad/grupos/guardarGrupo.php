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

// Obtener el último código registrado en la base de datos
$sql_last_code = "SELECT codigo FROM grupos ORDER BY codigo DESC LIMIT 1";
$result = $conn->query($sql_last_code);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $last_code = intval($row["codigo"]);
    $next_code = str_pad($last_code + 1, 2, "0", STR_PAD_LEFT); // Incrementar el último código y formatear como cuatro dígitos
} else {
    // Si no hay ningún código registrado aún, comenzamos desde 0001
    $next_code = "01";
}

// Procesar el formulario para guardar el grupo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $next_code; // Usar el siguiente código obtenido
    $nombre = $_POST["nombre"];
    
    // Insertar el nuevo grupo en la base de datos
    $sql = "INSERT INTO grupos (codigo, nombre) VALUES ('$codigo', '$nombre')";
    if ($conn->query($sql) === TRUE) {
        echo "GRUPO CREADO EXITOSAMENTE.";
    } else {
        echo "ERROR AL CREAR EL GRUPO: " . $conn->error;
    }
}

// Cerrar la conexión
$conn->close();
?>
