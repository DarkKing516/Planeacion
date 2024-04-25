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

    // Preparar la consulta para verificar códigos repetidos
    $check_sql = "SELECT codigo FROM articulos WHERE codigo = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $codigo_check);

    // Preparar la consulta para insertar datos
    $insert_sql = "INSERT INTO articulos (codigo, articulo, grupo, peso, valorRepo, codAlterno, tipo, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ssssssss", $codigo, $articulo, $grupo, $peso, $valorRepo, $codAlterno, $tipo, $estado);

    // Verificar si la consulta se preparó correctamente
    if (!$check_stmt || !$insert_stmt) {
        die("ERROR AL PREPARAR LA CONSULTA: " . $conn->error);
    }

    // Recorrer las líneas del archivo CSV e insertar los datos en la base de datos
    foreach ($lines as $line) {
        // Dividir la línea en datos separados por punto y coma (;)
        $data = explode(";", $line);

        // Asignar valores a las variables
        list($codigo, $articulo, $grupo, $peso, $valorRepo, $codAlterno, $tipo, $estado) = $data;

        // Verificar si el código ya existe en la base de datos
        $codigo_check = $codigo;
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            echo "CÓDIGO DUPLICADO ENCONTRADO: $codigo\n"; // Mensaje de depuración
            die("AL MENOS UN CODIGO DE LOS QUE ESTÁS INTENTANDO INGRESAR, YA EXISTE EN LA BASE DE DATOS Y POR ENDE NO SE GUARDARÁ ESTE ARCHIVO");
        }

        // Ejecutar la consulta para insertar datos
        if (!$insert_stmt->execute()) {
            die("ERROR AL INSERTAR DATOS: " . $insert_stmt->error);
        }
    }

    // Cerrar las consultas
    $check_stmt->close();
    $insert_stmt->close();

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
    <title>GESTIÓN DE ARTÍCULOS.</title>
    <!-- Estilos CSS -->
    <link rel="stylesheet" type="text/css" href="articulos.css">
    
</head>
<body>

<a href="/productividad/menu.php">
        <button>
            IR AL MENÚ
        </button>
    </a>

    <h1>GESTIÓN DE ARTÍCULOS.</h1>

    <!-- Formulario para cargar y guardar datos -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">

    <!-- Etiqueta y entrada para seleccionar archivo CSV -->
    <label for="csvFileInput" class="custom-file-upload" style="color: #244183; font-weight: bold;"></label>
    <input type="file" id="csvFileInput" accept=".csv" onchange="updateFileName(this)" />

    <!-- Botón para cargar CSV (tipo button) -->
    <button type="button" onclick="leerCSV()" class="custom-file-upload">CARGAR CSV</button>

    <!-- Botón para guardar datos (tipo submit) -->
    <button type="button" onclick="guardarDatos()" class="custom-file-upload">GUARDAR DATOS</button>

</form>

    <div id="tablaDatos"></div>

    <script type="text/javascript" src="articulos.js" defer></script>

</body>
</html>
