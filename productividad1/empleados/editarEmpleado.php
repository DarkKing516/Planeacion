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

// Proceso para actualizar el estado si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $estado = $_POST['estado'];
    $nombre = $_POST['nombre'];
    $cargo = $_POST['cargo'];
    $sede = $_POST['sede']; // Obtener el valor seleccionado del campo de selección de sede

    $actualizarRegistro = "UPDATE empleados SET estado = '$estado', nombre = '$nombre', cargo = '$cargo', sede = '$sede' WHERE id = '$id'";
    $resultadoActualizar = $conn->query($actualizarRegistro);

    // Redireccionar a tablaEmpleados.php
    header("Location: tablaEmpleados.php");
    exit();
}

// Obtener el ID del empleado a editar
if(isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para seleccionar los datos del empleado por su ID
    $consulta = "SELECT * FROM empleados WHERE id = $id";
    $resultado = $conn->query($consulta);

    if ($resultado->num_rows == 1) {
        $empleado = $resultado->fetch_assoc();
    } else {
        echo "EMPLEADO NO ENCONTRADO";
        exit();
    }
} else {
    echo "ID DE EMPLEADO NO PROPORCIONADO";
    exit();
}

// Obtener las opciones de sede desde la base de datos
$sql_sede = "SELECT DISTINCT sede FROM sedes";
$resultado_sede = $conn->query($sql_sede);
$optionsSede = '';
if ($resultado_sede->num_rows > 0) {
    while ($row = $resultado_sede->fetch_assoc()) {
        // Verificar si esta opción es la sede actual del empleado y marcarla como seleccionada
        $selected = ($empleado['sede'] == $row['sede']) ? 'selected' : '';
        $optionsSede .= "<option value='{$row['sede']}' $selected>{$row['sede']}</option>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDITAR EMPLEADO.</title>
    <!-- Estilos CSS -->
    <link rel="stylesheet" type="text/css" href="editarEmpleado.css">
   
</head>
<body>

<a href="/productividad/menu.php">
    <button>
        IR AL MENÚ
    </button>
</a>

<a href="tablaEmpleados.php">
    <button>
        VOLVER A LA TABLA DE EMPLEADOS 
    </button>
</a>

<!-- Encabezado de la página -->
<h1>EDITAR EMPLEADO.</h1>

<form id="updateForm" method="post" action="">
    <label for="estado">ESTADO:</label>
    <select name="estado" id="estado">
        <option value="ACTIVO" <?php if($empleado['estado'] == 'ACTIVO') echo 'selected'; ?>>ACTIVO</option>
        <option value="INACTIVO" <?php if($empleado['estado'] == 'INACTIVO') echo 'selected'; ?>>INACTIVO</option>
    </select>

    <label for="cedula">CÉDULA:</label>
    <input type="text" id="cedula" name="cedula" value="<?php echo $empleado['cedula']; ?>" readonly>

    <label for="nombre">NOMBRE:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo $empleado['nombre']; ?>" required>

    <label for="cargo">CARGO:</label>
    <input type="text" id="cargo" name="cargo" value="<?php echo $empleado['cargo']; ?>" required>

    <label for="sede">SEDE:</label>
    <select id="sede" name="sede">
        <?php echo $optionsSede; ?>
    </select>

    <label for="fecha">FECHA DE INGRESO:</label>
    <input type="text" id="fecha" name="fecha" value="<?php echo $empleado['fecha']; ?>" readonly>

    <label for="basico">BÁSICO:</label>
    <input type="text" id="basico" name="basico" value="<?php echo $empleado['basico']; ?>" readonly>

    <label for="vHora">VALOR HORA:</label>
    <input type="text" id="vHora" name="vHora" value="<?php echo $empleado['vHora']; ?>" readonly>

    <input type="hidden" name="id" value="<?php echo $empleado['id']; ?>">

    <input type="submit" value="ACTUALIZAR" onclick="return confirmUpdate()">
</form>

<script>
    function confirmUpdate() {
        var result = confirm("¿ESTÁS SEGURO QUE QUIERES ACTUALIZAR ESTE EMPLEADO?");
        return result;
    }
</script>

</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$conn->close();
?>
