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
$server = "localhost"; // Servidor de la base de datos (puede variar según tu configuración)
$user = "root"; // Usuario de la base de datos (puede variar según tu configuración)
$pass = ""; // Contraseña de la base de datos (puede variar según tu configuración)
$db = "datosgenerales"; // Nombre de la base de datos que contiene la tabla 'articulos'

// Conexión a la base de datos
$conn = new mysqli($server, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("LA CONEXIÓN FALLÓ: " . $conn->connect_error);
}

// Verificar si se ha enviado un archivo CSV
if (isset($_FILES['csvFileInput']) && $_FILES['csvFileInput']['error'] == UPLOAD_ERR_OK) {
    // Ruta temporal del archivo
    $file_temp = $_FILES['csvFileInput']['tmp_name'];

    // Leer el contenido del archivo CSV
    $csv_data = file_get_contents($file_temp);

    // Dividir el contenido en líneas
    $lines = explode("\n", $csv_data);

    // Preparar la consulta para insertar datos
    $sql = "INSERT INTO empleados (estado, cedula, nombre, cargo, sede, fecha, basico, vHora) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Verificar si la consulta se preparó correctamente
    if (!$stmt) {
        die("ERROR AL PREPARAR LA CONSULTA: " . $conn->error);
    }

    // Bindear los parámetros
    $stmt->bind_param("ssssssss", $estado, $cedula, $nombre, $cargo, $sede, $fecha, $basico, $vHora);

    // Recorrer las líneas del archivo CSV e insertar los datos en la base de datos
    foreach ($lines as $line) {
        // Dividir la línea en datos separados por punto y coma (;)
        $data = explode(";", $line);

        // Asignar valores a las variables
        list($estado, $cedula, $nombre, $cargo, $sede, $fecha, $basico, $vHora) = $data;

        // Ejecutar la consulta
        if (!$stmt->execute()) {
            die("ERROR AL INSERTAR DATOS: " . $stmt->error);
        }
    }

    // Cerrar la consulta
    $stmt->close();

    // Mostrar mensaje de éxito
    echo "<script>alert('LOS DATOS SE HAN GUARDADO CORRECTAMENTE.');</script>";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTIÓN DE EMPLEADOS.</title>
    <!-- Estilos CSS -->
    <link rel="stylesheet" type="text/css" href="empleados.css">
    
</head>
<body>

<a href="/productividad/menu.php">
        <button>
            IR AL MENÚ
        </button>
    </a>

    <h1>GESTIÓN DE EMPLEADOS.</h1>

    <!-- Formulario para cargar y guardar datos -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">

    <!-- Etiqueta y entrada para seleccionar archivo CSV -->
    <label for="csvFileInput" class="custom-file-upload" style="color: #244183; font-weight: bold;"></label>
    <input type="file" id="csvFileInput" accept=".csv" onchange="updateFileName(this)" />

    <!-- Botón para cargar CSV (tipo button) -->
    <button type="button" onclick="leerCSV()" class="custom-file-upload">CARGAR CSV</button>

    <!-- Botón para guardar datos (tipo submit) -->
    <button type="submit" class="custom-file-upload">GUARDAR DATOS</button>

</form>

    <div id="tablaDatos"></div>

    <script type="text/javascript" src="empleados.js" defer></script>

</body>
</html>
