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
$server = "localhost";
$user = "root";
$pass = "";
$db = "datosgenerales";

// Conexión a la base de datos
$conn = new mysqli($server, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("LA CONEXIÓN FALLÓ: " . $conn->connect_error);
} else {
    echo "CONEXIÓN EXITOSA A LA BASE DE DATOS.<br>";
}

// Obtener los datos enviados mediante AJAX
$data = json_decode(file_get_contents("php://input"), true);

// Verificar si se recibieron datos válidos
if ($data !== null && is_array($data)) {
    // Preparar la consulta para insertar datos
    $sql = "INSERT INTO procesos (codigo, nombre, grupo) VALUES (?, ?, ?)";
    echo "Consulta SQL: $sql<br>"; // Mostrar la consulta SQL para depurar

    $stmt = $conn->prepare($sql);

    // Verificar si la consulta se preparó correctamente
    if (!$stmt) {
        die("ERROR AL PREPARAR LA CONSULTA: " . $conn->error);
    } else {
        echo "CONSULTA PREPARADA CORRECTAMENTE.<br>";
    }

    // Insertar datos en la base de datos
    foreach ($data as $row) {
        $codigo = $row['codigo'];
        $nombre = $row['nombre'];
        $grupo = $row['grupo'];

        echo "DATOS RECIBIDOS: Código: $codigo, Nombre: $nombre, Grupo: $grupo<br>"; // Mostrar datos recibidos para depurar

        $stmt->bind_param("sss", $codigo, $nombre, $grupo);
        if (!$stmt->execute()) {
            die("ERROR AL INSERTAR DATOS: " . $stmt->error);
        } else {
            echo "DATOS INSERTADOS CORRECTAMENTE.<br>";
        }
    }

    // Cerrar la consulta
    $stmt->close();
} else {
    echo "ERROR: NO SE RECIBIERON DATOS CORRECTAMENTE.";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
