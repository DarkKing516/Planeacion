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
$sql_tipos = "SELECT DISTINCT tipo FROM articulos";
$resultado_tipos = $conn->query($sql_tipos);
$optionsTipos = '';
if ($resultado_tipos->num_rows > 0) {
    while ($row = $resultado_tipos->fetch_assoc()) {
        $optionsTipos .= "<option value='{$row['tipo']}'>{$row['tipo']}</option>";
    }
}

// Consulta SQL para obtener los registros de la tabla 'articulos'
$sql_grupos = "SELECT DISTINCT grupo FROM articulos";
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
    <title>INFORMES DE ARTÍCULOS</title>
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="infoArticulos.css">
</head>
<body>
    <a href="/productividad/menu.php" class="menu-link">
        <button class="menu-button">IR AL MENÚ</button>
    </a>
    
    <a href="/productividad/informes/informes.php" class="menu-link">
        <button class="informes-button">VOLVER A INFIRMES</button>
    </a>

    <h1>INFORME DE ARTÍCULOS</h1>

    <!-- Contenedor de selectores -->
    <div class="select-container">
        <select name="tipo" id="tipo">
            <option value="">SELECCIONAR TIPO</option>
            <?php echo $optionsTipos ?>
        </select>

        <select name="grupo" id="grupo">
            <option value="">SELECCIONAR GRUPO</option>
            <?php echo $optionsGrupos ?>
        </select>

        <!-- Botón FILTRAR -->
        <button id="filtro-button" class="filtro-button">FILTRAR</button>

        <!-- Botón de descarga -->
        <button id="descarga-button" class="descarga-button">DESCARGAR</button>
    </div>

    <!-- Contenedor para la tabla de resultados -->
    <div id="tabla-articulos-container">
        <!-- La tabla de resultados se mostrará aquí -->
    </div>

    <!-- Script para manejar la solicitud AJAX y el filtrado de datos -->
    <script src="infoArticulos.js"></script>

</body>
</html>
