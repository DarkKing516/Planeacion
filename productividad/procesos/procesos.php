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
    die("ERROR DE CONEXIÓN: " . $conn->connect_error);
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el último código generado
    $last_code = getLastCode();

    // Generar el siguiente código en orden secuencial
    $codigo = generateNextCode($last_code);

    // Recoger los otros datos del formulario y convertirlos a mayúsculas
    $nombre = strtoupper($_POST["nombre"]);
    $grupo = strtoupper($_POST["grupo"]);

    // Preparar la consulta SQL para insertar los datos en la base de datos
    $sql = "INSERT INTO procesos (codigo, nombre, grupo) VALUES ('$codigo', '$nombre', '$grupo')";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        // Mostrar la alerta y redirigir
        echo "<script>alert('DATOS REGISTRADOS DE MANERA CORRECTA'); window.location.href = 'procesos.php';</script>";
    } else {
        echo "ERROR AL GUARDAR LOS DATOS: " . $conn->error;
    }
}

// Función para obtener el último código generado
function getLastCode() {
    global $conn;
    // Consultar la base de datos para obtener el último código generado
    $sql = "SELECT MAX(codigo) AS last_code FROM procesos";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $last_code = $row["last_code"];
    return $last_code;
}

// Función para generar el siguiente código en orden secuencial
function generateNextCode($last_code) {
    // Si no hay ningún código generado anteriormente, empezar desde "01"
    if (!$last_code) {
        return "01";
    } else {
        // Incrementar el último código en uno
        $next_code = intval($last_code) + 1;
        // Formatear el nuevo código para que tenga siempre dos dígitos
        return sprintf("%02d", $next_code);
    }
}

// Función para obtener las opciones de la tabla 'grupos' sin duplicados
function getGruposOptions() {
    global $conn;
    $options = array();
    // Consulta SQL para obtener las opciones de la tabla 'grupos' sin duplicados
    $sql = "SELECT DISTINCT nombre FROM grupos";
    $result = $conn->query($sql);
    // Almacena las opciones en un arreglo
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $options[] = $row["nombre"];
        }
    }
    return $options;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTIÓN DE PROCESOS</title>
    <link rel="stylesheet" type="text/css" href="procesos.css">
    <script src="procesos.js" defer></script> <!-- Se incluye el archivo procesos.js -->
</head>
<body>

<a href="/productividad/menu.php" class="menu-btn"><button>IR AL MENÚ</button></a>
<a href="procesos.php" class="refresh-btn"><button>REFRESCAR</button></a>

<h1>GESTIÓN DE PROCESOS.</h1>

<!-- Formulario para agregar nuevos procesos -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <!-- Campo de código generado automáticamente -->
    <label class="front-label">CÓDIGO:</label>
    <input type="text" name="codigo" readonly>
    <!-- Campo para el nombre del proceso -->
    <label class="front-label">NOMBRE:</label>
    <input type="text" name="nombre" required>
    <!-- Selección del grupo del proceso -->
    <label class="front-label">GRUPO:</label>
    <select name="grupo" required>
        <option value="" disabled selected>SELECCIONE UN GRUPO</option>
        <?php
        $grupos_options = getGruposOptions();
        foreach ($grupos_options as $option):
            ?>
            <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
        <?php endforeach; ?>
    </select>
    <!-- Botón para guardar el proceso -->
    <input type="submit" value="GUARDAR" class="save-btn">
</form>

<!-- Tabla para mostrar los registros de procesos -->
<h2>REGISTROS ALMACENADOS EN LA TABLA PROCESOS.</h2>
<div id="tabla-container">
    <table>
        <tr>
            <th>CÓDIGO</th>
            <th>NOMBRE</th>
            <th>GRUPO</th>
            <th>ACCIONES</th>
        </tr>

        <?php
        // Consulta SQL para obtener los registros de procesos
        $consulta = "SELECT id, codigo, nombre, grupo FROM procesos";
        $resultado = $conn->query($consulta);

        // Verificar si hay registros
        if ($resultado->num_rows > 0) {
            // Recorrer los registros y mostrarlos en la tabla
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $fila["codigo"] . "</td>";
                echo "<td>" . $fila["nombre"] . "</td>";
                echo "<td>" . $fila["grupo"] . "</td>";
                echo "<td>";
                // Botón para eliminar un proceso vinculado a eliminarProceso.php
                echo "<a href='eliminarProceso.php?id=" . $fila["id"] . "' onclick='return confirmarEliminar();'><button style='width: 120px; height: 40px; padding: 5px 15px; background-color: #FF0000; color: #FFFFFF; border: 2px solid #FF0000; border-radius: 10px; padding: 10px 20px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background-color 0.3s, color 0.3s, border-color 0.3s;'>ELIMINAR</button></a>";
                // Botón para editar un proceso
                echo "<button type='button' onclick='mostrarEditarModal(" . $fila["id"] . ", \"" . $fila["codigo"] . "\", \"" . $fila["nombre"] . "\", \"" . $fila["grupo"] . "\");' style='position: relative; background-color: #244183; color: #FFFFFF; padding: 10px 10px; border: none; border-radius: 10px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background-color 0.3s, color 0.3s, border-color 0.3s;' class='edit-btn'>EDITAR</button>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            // Mostrar mensaje si no hay registros
            echo "<tr><td colspan='4'>NO HAY REGISTROS EN LA TABLA.</td></tr>";
        }
        ?>
    </table>
</div>

<!-- Modal para editar procesos -->
<div id="editarProcesoModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>EDITAR PROCESO</h2>
        <form id="editarProcesoForm" action="actualizarProceso.php" method="POST"> <!-- Cambiado el action -->
            <input type="hidden" name="id" id="editId">
            <label for="editCodigo">CÓDIGO:</label>
            <input type="text" id="editCodigo" name="codigo" readonly><br>
            <label for="editNombre">NOMBRE:</label>
            <input type="text" id="editNombre" name="nuevoNombre" required><br>
            <!-- Campo de selección para el grupo -->
            <label for="editGrupo">GRUPO:</label>
            <select name="nuevoGrupo" id="editGrupo" required>
                <option value="" disabled selected>SELECCIONE UN GRUPO</option>
                <?php
                $grupos_options = getGruposOptions();
                foreach ($grupos_options as $option):
                    ?>
                    <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                <?php endforeach; ?>
            </select><br>
            <!-- Botón para actualizar con confirmación -->
            <input type="button" onclick="confirmarEditarProceso();" value="ACTUALIZAR" style="cursor: pointer; background-color: #244183; color: #FFFFFF; padding: 10px 20px; border: none; border-radius: 10px; font-size: 16px; font-weight: bold; transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#0F3057'" onmouseout="this.style.backgroundColor='#244183'">
        </form>
    </div>
</div>

<script>
    // Obtener el último código generado
    var lastCode = <?php echo json_encode(getLastCode()); ?>;

    // Obtener las opciones de grupo y eliminar duplicados
    var gruposOptions = <?php echo json_encode(array_unique(getGruposOptions())); ?>;
</script>

</body>
</html>
