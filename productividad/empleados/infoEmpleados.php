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

// Consulta SQL para obtener los registros de la tabla 'empleados'
$sql_estado = "SELECT DISTINCT estado FROM empleados";
$resultado_estado = $conn->query($sql_estado);
$optionsEstado = '';
if ($resultado_estado->num_rows > 0) {
    while ($row = $resultado_estado->fetch_assoc()) {
        $optionsEstado .= "<option value='{$row['estado']}'>{$row['estado']}</option>";
    }
}

// Consulta SQL para obtener los registros de la tabla 'empleados'
$sql_cargo = "SELECT DISTINCT cargo FROM empleados";
$resultado_cargo = $conn->query($sql_cargo);
$optionsCargo = '';
if ($resultado_cargo->num_rows > 0) {
    while ($row = $resultado_cargo->fetch_assoc()) {
        $optionsCargo .= "<option value='{$row['cargo']}'>{$row['cargo']}</option>";
    }
}

// Consulta SQL para obtener los registros de la tabla 'empleados'
$sql_sede = "SELECT DISTINCT sede FROM empleados";
$resultado_sede = $conn->query($sql_sede);
$optionsSede = '';
if ($resultado_sede->num_rows > 0) {
    while ($row = $resultado_sede->fetch_assoc()) {
        $optionsSede .= "<option value='{$row['sede']}'>{$row['sede']}</option>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INFORMES DE EMPLEADOS</title>
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="infoEmpleados.css">
</head>
<body>
    <a href="/productividad/menu.php" class="menu-link">
        <button class="menu-button">IR AL MENÚ</button>
    </a>

    <a href="/productividad/informes/informes.php" class="menu-link">
        <button class="informes-button">VOLVER A INFIRMES</button>
    </a>

    <h1>INFORME DE EMPLEADOS</h1>

    <!-- Contenedor de selectores -->
    <div class="select-container">
        <select name="estado" id="estado">
            <option value="">SELECCIONAR ESTADO</option>
            <?php echo $optionsEstado ?>
        </select>

        <select name="cargo" id="cargo">
            <option value="">SELECCIONAR CARGO</option>
            <?php echo $optionsCargo ?>
        </select>
        
        <select name="sede" id="sede">
            <option value="">SELECCIONAR SEDE</option>
            <?php echo $optionsSede ?>
        </select>

        <!-- Botón FILTRAR -->
        <button id="filtro-button" class="filtro-button">FILTRAR</button>

        <!-- Botón de descarga -->
        <button id="descarga-button" class="descarga-button">DESCARGAR</button>
    </div>

    <!-- Contenedor para la tabla de resultados -->
    <div id="tabla-empleados-container">
        <!-- La tabla de resultados se mostrará aquí -->
    </div>

    <!-- Script para manejar la solicitud AJAX y el filtrado de datos -->
    <script src="infoEmpleados.js"></script>

</body>
</html>
