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

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datosgenerales";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el código del estándar a editar, si está disponible
$codigo_estandar = isset($_GET['codigo']) ? $_GET['codigo'] : null;

// Verificar si se proporcionó un código de estándar válido
if ($codigo_estandar === null) {
    echo "<script>window.location.href = 'estandar.php';</script>";
    exit; // Terminar la ejecución del script
}

// Consulta SQL para obtener los datos del estándar a editar
$sql = "SELECT nombre_estandar, t_estandar, cod_grupo, cod_proceso, cod_articulo, peso, grupo FROM estandar WHERE cod_estandar = '$codigo_estandar'";
$result = $conn->query($sql);

// Verificar si se encontraron resultados
if ($result->num_rows > 0) {
    // Obtener los datos del registro
    $row = $result->fetch_assoc();
    $nombre_estandar = $row["nombre_estandar"];
    $t_estandar = $row["t_estandar"];
    $cod_grupo = $row["cod_grupo"];
    $cod_proceso = $row["cod_proceso"];
    $cod_articulo = $row["cod_articulo"];
    $peso = $row["peso"];
    $grupo = $row["grupo"];
}

// Consulta SQL para obtener los nombres de los grupos
$sql_grupos = "SELECT codigo, nombre FROM grupos";
$result_grupos = $conn->query($sql_grupos);

// Consulta SQL para obtener los nombres de los procesos
$sql_procesos = "SELECT codigo, nombre FROM procesos";
$result_procesos = $conn->query($sql_procesos);

// Consulta SQL para obtener los nombres de los artículos
$sql_articulos = "SELECT codigo, articulo FROM articulos";
$result_articulos = $conn->query($sql_articulos);

// Cerrar la conexión
//$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDITAR ESTÁNDAR</title>
    <link rel="stylesheet" href="editarEstandar.css">
</head>
<body>
    
<a href="/productividad/menu.php" class="boton-menu">IR AL MENÚ</a>
<a href="estandar.php" class="boton-menu">VOLVER A GESTIÓN DE ESTÁNDAR</a>

    <h1>EDITAR ESTÁNDAR</h1>
    <form id="form-editar" action="actualizarEstandar.php" method="post">
        <input type="hidden" id="cod_estandar_editar" name="cod_estandar" value="<?php echo $codigo_estandar; ?>"> <!-- Campo oculto para almacenar el código del estándar -->
        <div class="container" id="container-grupo">
            <label for="grupo" style="position: relative">GRUPO:</label><br>
            <input type="text" id="cod_grupo" name="cod_grupo" value="<?php echo $cod_grupo; ?>" readonly style="width: 48%;"><br>
            <input type="text" id="grupo" name="grupo" value="<?php echo $grupo; ?>" readonly><br>
                <?php
                //if ($result_grupos->num_rows > 0) {
                    //while($row = $result_grupos->fetch_assoc()) {
                        //$selected = ($row["codigo"] == $cod_grupo) ? "selected" : "";
                        //echo "<option value='".$row["codigo"]."' $selected>".$row["nombre"]."</option>";
                    //}
                //}
                ?>
            </select>
        </div><br>

        <div class="container" id="container-proceso">
            <label for="proceso" style="position: relative">NUEVO PROCESO:</label><br>
            <input type="text" id="cod_proceso" name="cod_proceso" value="<?php echo $cod_proceso; ?>" readonly style="width: 48%;"><br>
            <select id="proceso" name="proceso" required onchange="updateCodigoProceso()" style="width: 48%; font-weight: bold; color: #244183; display: block; margin-bottom: 10px; text-align: center; position: relative; z-index: 2; padding: 10px; border: 2px solid #244183; border-radius: 15px;">
                <option value="">SELECCIONE UN PROCESO</option>
                <?php
                if ($result_procesos->num_rows > 0) {
                    while($row = $result_procesos->fetch_assoc()) {
                        $selected = ($row["codigo"] == $cod_proceso) ? "selected" : "";
                        echo "<option value='".$row["codigo"]."' $selected>".$row["nombre"]."</option>";
                    }
                }
                ?>
            </select>
        </div><br>

        <div class="container" id="container-articulo">
            <label for="articulo">NUEVO ARTÍCULO:</label><br>
            <input type="text" id="cod_articulo" name="cod_articulo" value="<?php echo $cod_articulo; ?>" readonly style="width: 48%;"><br>
            <select id="articulo" name="articulo" required onchange="updateCodigoArticulo(), obtenerPeso()" style="width: 48%; font-weight: bold; color: #244183; display: block; margin-bottom: 10px; text-align: center; position: relative; z-index: 2; padding: 10px; border: 2px solid #244183; border-radius: 15px;">
                <option value="">SELECCIONE UN ARTÍCULO</option>
                <?php
                if ($result_articulos->num_rows > 0) {
                    while($row = $result_articulos->fetch_assoc()) {
                        $selected = ($row["codigo"] == $cod_articulo) ? "selected" : "";
                        echo "<option value='".$row["codigo"]."' $selected>".$row["articulo"]."</option>";
                    }
                }
                ?>
            </select><br>
            <label for="peso">PESO:</label><br>
            <input type="decimal" id="peso" name="peso" value="<?php echo $peso; ?>" readonly><br>
        </div><br>
        <div class="container" id="container-nombre-tiempo">
            <div class="label-container">
                <label for="nombre_estandar_editar">NOM. ESTÁNDAR:</label>
            </div>
            <input type="text" id="nombre_estandar_editar" name="nombre_estandar" readonly value="<?php echo $nombre_estandar; ?>"><br>
            <label for="codigo_estandar">CÓD. ESTÁNDAR:</label><br>
            <input type="text" id="codigo_estandar" name="codigo_estandar" value="<?php echo $codigo_estandar; ?>" readonly ><br>
            <div class="label-container">
                <label for="t_estandar_editar">NUEVO T. ESTÁNDAR (MIN):</label>
            </div>
            <input type="number" id="t_estandar_editar" name="t_estandar" min="0.1" step="0.1" value="<?php echo $t_estandar; ?>"><br>
        </div>

        <!-- Botón de Actualizar con el evento onclick -->
        <input type="submit" value="ACTUALIZAR" class="boton-actualizar" onclick="return confirmarActualizacion()">

    </form>
    
<script src="editarEstandar.js"></script>

</body>
</html>
