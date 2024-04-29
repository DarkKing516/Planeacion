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

// Guardar el nombre de usuario en una variable de sesión
$usuario_actual = $_SESSION['usuario'];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTIÓN DE PLANEACIÓN</title>
    <link rel="stylesheet" href="planeacion.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="planeacion.js"></script>

</head>

<body>

    <!-- Botón "Ir al menú" fuera del formulario -->
    <a href="/productividad/menu.php" class="boton-menu">
        IR AL MENÚ
    </a>

    <form id="form-estandar" method="POST" action="procesar_formulario.php">
        <?php
        // Conexión a la base de datos (reemplaza los valores con los de tu configuración)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "datosgenerales";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar la conexión
        if ($conn->connect_error) {
            die("CONEXIÓN FALLIDA: " . $conn->connect_error);
        }

        // Obtener empleados
        $sql_empleados = "SELECT DISTINCT sede FROM empleados";
        $result_empleados = $conn->query($sql_empleados);

        $sql_personal = "SELECT sede FROM sedes";
        $result_personal = $conn->query($sql_personal);

        // Cerrar la conexión
        $conn->close();
        ?>

        <h1>GESTIÓN DE PLANEACIÓN</h1>
        <div class="container-formulario">
            <div class="container-izquierda">

                <label for="sede">SEDE:</label><br>
                <select id="sede" name="sede" required>
                    <option value="">SELECCIONE UNA SEDE</option>
                    <?php
                    if ($result_empleados->num_rows > 0) {
                        while ($row = $result_empleados->fetch_assoc()) {
                            echo "<option value='" . $row["sede"] . "'>" . $row["sede"] . "</option>";
                        }
                    }
                    ?>
                </select>


            </div><br>
            <div class="container-izquierda">
                <label for="usuario">USUARIO:</label><br>
                <input type="text" id="usuario" name="usuario" value="<?php echo $usuario_actual; ?>" readonly required>
                <input type="number" id="indice" name="indice" placeholder="Selecciona el indice del empleado" required>
            </div><br>

            <div class="container-derecha">
                <div id="container-tiempo-estandar">
                    <label for="fecha">FECHA PLANEACION:</label><br>
                    <input type="date" id="fecha" name="fecha" placeholder="DD/MM/AAAA" style="text-transform: uppercase;" required>
                </div>

                <div id="container-tiempo-estandar">
                    <label for="per_autorizado">PERSONAL AUTORIZADO:</label><br>
                    <input type="number" id="per_autorizado" name="per_autorizado" readonly><br>
                </div>
            </div>
        </div>

        <table id="tabla-empleados"></table>

        <?php
        // Conexión a la base de datos (reemplaza los valores con los de tu configuración)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "datosgenerales";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar la conexión
        if ($conn->connect_error) {
            die("CONEXIÓN FALLIDA: " . $conn->connect_error);
        }

        // Consulta para obtener los datos de la tabla 'grupos'
        $sql_grupos = "SELECT * FROM grupos";
        $result_grupos = $conn->query($sql_grupos);

        // Cerrar la conexión
        $conn->close();
        ?>

        <select name="grupos" class="grupo">
            <option value="default">SELECCIONE UN GRUPO</option>
            <?php
            // Verificar si se obtuvieron resultados de la consulta
            if ($result_grupos->num_rows > 0) {
                // Iterar sobre los resultados y generar las opciones del select
                while ($row = $result_grupos->fetch_assoc()) {
                    echo "<option value='" . $row["nombre"] . "'>" . $row["nombre"] . "</option>";
                }
            }
            ?>
        </select>

        <button type="submit" id="guardarDatos">GUARDAR</button>

    </form>

</body>

</html>