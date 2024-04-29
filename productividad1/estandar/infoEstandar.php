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

// Consulta SQL para obtener los registros de la tabla 'articulos'
$sql_proceso = "SELECT DISTINCT proceso FROM estandar";
$resultado_proceso = $conn->query($sql_proceso);
$optionsProceso = '';
if ($resultado_proceso->num_rows > 0) {
    while ($row = $resultado_proceso->fetch_assoc()) {
        $optionsProceso .= "<option value='{$row['proceso']}'>{$row['proceso']}</option>";
    }
}

// Consulta SQL para obtener los registros de la tabla 'articulos'
$sql_grupos = "SELECT DISTINCT grupo FROM estandar";
$resultado_grupos = $conn->query($sql_grupos);
$optionsGrupos = '';
if ($resultado_grupos->num_rows > 0) {
    while ($row = $resultado_grupos->fetch_assoc()) {
        $optionsGrupos .= "<option value='{$row['grupo']}'>{$row['grupo']}</option>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INFORMES DE ESTÁNDAR</title>
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="infoEstandar.css">
</head>
<body>

    <a href="/productividad/menu.php" class="menu-link">
        <button class="menu-button">IR AL MENÚ</button>
    </a>

    <a href="/productividad/informes/informes.php" class="menu-link">
        <button class="informes-button">VOLVER A INFORMES</button>
    </a>

    <h1>INFORME DE ESTÁNDAR</h1>

    <!-- Contenedor de selectores -->
    <div class="select-container">
        <select name="grupo" id="grupo">
            <option value="">SELECCIONAR GRUPO</option>
            <?php echo $optionsGrupos ?>
        </select>

        <select name="proceso" id="proceso">
            <option value="">SELECCIONAR PROCESO</option>
            <?php echo $optionsProceso ?>
        </select>

        <!-- Botón FILTRAR -->
        <button id="filtro-button" class="filtro-button">FILTRAR</button>

        <!-- Botón de descarga -->
        <button id="descarga-button" class="descarga-button">DESCARGAR</button>
    </div>

    <!-- Contenedor para la tabla de resultados -->
    <div id="tabla-estandar-container">
        <!-- La tabla de resultados se mostrará aquí -->
    </div>

    <!-- Script para manejar la solicitud AJAX y el filtrado de datos -->
    <script src="infoEstandar.js"></script>

</body>
</html>
