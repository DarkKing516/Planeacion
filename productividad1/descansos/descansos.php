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
// Aquí va tu conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "datosgenerales");

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener las opciones disponibles para el campo "SEDE" de la tabla "sedes"
$sede_options_query = "SELECT sede FROM sedes";
$sede_options_result = $conn->query($sede_options_query);

// Array para almacenar las opciones de sede
$sede_options = array();

if ($sede_options_result->num_rows > 0) {
    while ($row = $sede_options_result->fetch_assoc()) {
        $sede_options[] = $row['sede'];
    }
}

// Obtener el último código almacenado en la base de datos
$ultimoCodigoQuery = "SELECT MAX(cod_descanso) AS ultimo_codigo FROM descansos";
$ultimoCodigoResult = $conn->query($ultimoCodigoQuery);

if ($ultimoCodigoResult->num_rows > 0) {
    $row = $ultimoCodigoResult->fetch_assoc();
    $ultimoCodigo = $row["ultimo_codigo"];
    // Incrementar el último código en 1 para generar el siguiente código
    $siguienteCodigo = sprintf("%02d", intval($ultimoCodigo) + 1);
} else {
    // Si no hay registros en la tabla, empezar desde 01
    $siguienteCodigo = "01";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTIÓN DE DESCANSOS</title>
    <link rel="stylesheet" href="descansos.css">
</head>
<body>

<a href="/productividad/menu.php">
        <button>IR AL MENÚ</button>
    </a>
    <a href="descansos.php"><button type="button">REFRESCAR</button></a>

    <h1>GESTIÓN DE DESCANSOS.</h1>

    <form id="formulario" action="guardarDescanso.php" method="post" onsubmit="return guardarDescanso()">
        <label class="front-label" for="cod_descanso">CÓDIGO:</label>
        <input type="text" id="cod_descanso" name="cod_descanso" value="<?php echo $siguienteCodigo; ?>" readonly>
        <label class="front-label" for="nom_descanso">NOMBRE:</label>
        <input type="text" id="nom_descanso" name="nom_descanso" placeholder="INGRESE EL NOMBRE" required>
        <label for="sede" class="front-label">SEDE:</label>
        <select id="sede" name="sede" required>
            <option value="">SELECCIONE LA SEDE</option>
            <?php foreach ($sede_options as $sede_option) { ?>
                <option value="<?php echo $sede_option; ?>"><?php echo $sede_option; ?></option>
            <?php } ?>
        </select>
        <label class="front-label" for="ini_descanso">HORA DE INICIO:</label>
        <input type="time" id="ini_descanso" name="ini_descanso" onchange="calcularTiempo()" oninput="calcularTiempo()" required>
        <label class="front-label" for="fin_descanso">HORA FIN:</label>
        <input type="time" id="fin_descanso" name="fin_descanso" onchange="calcularTiempo()" oninput="calcularTiempo()" required>
        <label class="front-label" for="tiempo_des">TIEMPO DE DESCANSO:</label>
        <input type="text" id="tiempo_des" name="tiempo_des" placeholder="AQUÍ SE MOSTRARÁ EL TIEMPO TRANSCURRIDO." readonly>
        <button type="submit">GUARDAR</button>
    </form>

    <h1>REGISTROS ALMACENADOS EN LA TABLA DESCANSOS.</h1>
    <?php
        // Consultar los registros almacenados en la tabla 'descansos'
        $sql = "SELECT cod_descanso, nom_descanso, sede, ini_descanso, fin_descanso, tiempo_des FROM descansos";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>CÓDIGO</th><th>NOMBRE</th><th>SEDE</th><th>INICIO</th><th>FIN</th><th>TIEMPO</th><th>ACCIONES</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["cod_descanso"]. "</td>";
                echo "<td>" . $row["nom_descanso"]. "</td>";
                echo "<td>" . $row["sede"]. "</td>";
                echo "<td>" . $row["ini_descanso"]. "</td>";
                echo "<td>" . $row["fin_descanso"]. "</td>";
                echo "<td>" . $row["tiempo_des"]. "</td>";
                echo "<td>";
                echo "<a href='#' onclick='eliminarDescanso(" . $row["cod_descanso"] . ")' class='btn-eliminar'>ELIMINAR</a>";
                echo "<a href='#' onclick='abrirModalEditar(" . $row["cod_descanso"] . ")' class='btn-editar'>EDITAR</a>";
                echo "</td>";
                echo "</tr>"; 
            }
            echo "</table>";
        } else {
            echo "NO HAY REGISTROS ALMACENADOS EN LA TABLA.";
        }
    ?>

  <!-- Modal para editar -->
  <div id="modalEditar" class="modal">
        <div class="modal-contenido">
            <span class="cerrar" onclick="cerrarModalEditar()">&times;</span>
            <div id="formularioEditar"></div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="descansos.js"></script>

    <?php
    // Manejo de alertas
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["status"])) {
        if ($_GET["status"] === "success") {
            echo "<script>alert('DESCANSO GUARDADO CORRECTAMENTE.')</script>";
        } elseif ($_GET["status"] === "error") {
            echo "<script>alert('ERROR AL GUARDAR EL DESCANSO.')</script>";
        }
    }
    ?>

</body>
</html>
