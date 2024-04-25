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
$conn = new mysqli($server, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("ERROR DE CONEXIÓN: " . $conn->connect_error);
}

// Procesar el formulario cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $codigo = $_POST['codigo'];
    $articulo = $_POST['articulo'];
    $grupo = $_POST['grupo'];
    $peso = $_POST['peso'];
    $valorRepo = $_POST['valorRepo'];
    $tipo = $_POST['tipo'];
    
    // Validar los datos recibidos
    if (empty($codigo) || empty($articulo) || empty($grupo) || empty($peso) || empty($valorRepo) || empty($tipo)) {
        echo "POR FAVOR, COMPLETE TODOS LOS CAMPOS DEL FORMULARIO.";
    } else {
        // Verificar si el código ya existe en la base de datos
        $codigo_exist_query = "SELECT articulo FROM articulos WHERE codigo = ?";
        $stmt_exist = $conn->prepare($codigo_exist_query);
        $stmt_exist->bind_param("s", $codigo);
        $stmt_exist->execute();
        $stmt_exist->store_result();
        
        if ($stmt_exist->num_rows > 0) {
            $stmt_exist->bind_result($articulo_existente);
            $stmt_exist->fetch();
            echo "<script>alert('EL CÓDIGO QUE INGRESASTE YA EXISTE, Y ESTÁ ASIGNADO AL ARTÍCULO \"$articulo_existente\"');</script>";
        } else {
            // Insertar el nuevo artículo en la base de datos
            $insertarQuery = "INSERT INTO articulos (codigo, articulo, grupo, peso, valorRepo, tipo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertarQuery);
            $stmt->bind_param("ssssds", $codigo, $articulo, $grupo, $peso, $valorRepo, $tipo);
            
            if ($stmt->execute()) {
                echo "<script>alert('ARTÍCULO INGRESADO CORRECTAMENTE');</script>";
            } else {
                echo "ERROR AL INGRESAR EL ARTÍCULO: " . $conn->error;
            }
        }
        
        // Cerrar la consulta
        $stmt_exist->close();
    }
}

// Obtener las opciones disponibles para el campo "grupo" de la tabla "grupos"
$grupo_options_query = "SELECT nombre FROM grupos";
$grupo_options_result = $conn->query($grupo_options_query);

// Array para almacenar las opciones de grupo
$grupo_options = array();

if ($grupo_options_result->num_rows > 0) {
    while ($row = $grupo_options_result->fetch_assoc()) {
        $grupo_options[] = $row['nombre'];
    }
}

// Obtener las opciones disponibles para el campo "tipo" de la tabla "articulos"
$tipo_options_query = "SELECT DISTINCT nombre FROM tipos";
$tipo_options_result = $conn->query($tipo_options_query);

// Array para almacenar las opciones de tipo
$tipo_options = array();

if ($tipo_options_result->num_rows > 0) {
    while ($row = $tipo_options_result->fetch_assoc()) {
        $tipo_options[] = $row['nombre'];
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INGRESAR ARTÍCULO.</title>
    <!-- Estilos CSS -->
    <link rel="stylesheet" type="text/css" href="crearArticulo.css">

    <!-- Script de JavaScript -->
    <script>
    // Función para verificar el código cuando se cambia el campo de código
    function verificarCodigo() {
        var codigo = document.getElementById('codigo').value;

        // Realizar una solicitud AJAX para verificar si el código ya existe
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'verificarCodigo.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = xhr.responseText;
                if (response != "") {
                    alert(response);
                }
            }
        };
        xhr.send('codigo=' + codigo);
    }
    </script>
</head>
<body>

<a href="/productividad/menu.php">
        <button>
            IR AL MENÚ
        </button>
    </a>

    <a href="tablaArticulos.php"><button type="button">IR A LA TABLA DE ARTÍCULOS</button></a>

    <h1>CREAR UN ARTÍCULO.</h1>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="codigo" class="front-label">CÓDIGO:</label>
        <input type="text" id="codigo" name="codigo" required onblur="verificarCodigo()">

        <label for="articulo" class="front-label">ARTÍCULO:</label>
        <input type="text" id="articulo" name="articulo" required>

        <label for="grupo" class="front-label">GRUPO:</label>
        <select id="grupo" name="grupo" required>
            <option value="">SELECCIONE EL GRUPO</option>
            <?php foreach ($grupo_options as $grupo_option) { ?>
                <option value="<?php echo $grupo_option; ?>"><?php echo $grupo_option; ?></option>
            <?php } ?>
        </select>

        <label for="peso" class="front-label">PESO:</label>
        <input type="decimal" id="peso" name="peso" required>

        <label for="valorRepo" class="front-label">VALOR REPOSICIÓN:</label>
        <input type="text" id="valorRepo" name="valorRepo" required>
        
        <label for="tipo" class="front-label">TIPO:</label>
        <select id="tipo" name="tipo" >
            <option value="">SELECCIONE EL TIPO</option>
            <?php foreach ($tipo_options as $tipo_option) { ?>
                <option value="<?php echo $tipo_option; ?>"><?php echo $tipo_option; ?></option>
            <?php } ?>
        </select>

        <label for="estado" class="front-label">ESTADO:</label>
        <select id="estado" name="estado">
            <option value="">SELECCIONE EL ESTADO</option>
                <option>ACTIVO</option>
                <option>INACTIVO</option>
        </select>

        <input type="submit" value="GUARDAR">
    </form>

</body>
</html>
