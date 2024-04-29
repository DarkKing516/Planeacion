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
// Verificar si se recibió el código del descanso a editar
if(isset($_GET['id'])) {
    // Obtener el código del descanso desde la URL
    $codigoDescanso = $_GET['id'];

    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "datosgenerales";

    // Crear conexión a la base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Verificar la conexión
    if ($conn->connect_error) {
        die("CONEXIÓN FALLIDA: " . $conn->connect_error);
    }

    // Consultar la información del descanso con el código recibido
    $sql = "SELECT cod_descanso, nom_descanso, sede, ini_descanso, fin_descanso, tiempo_des FROM descansos WHERE cod_descanso = $codigoDescanso";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Mostrar el formulario para editar el descanso
        $row = $result->fetch_assoc();
        ?>

        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Editar Descanso</title>
        </head>
        <body>

        <h1>EDITAR DESCANSO</h1>

        <form action="actualizarDescanso.php" method="post" onsubmit="return confirm('¿ESTÁS SEGURO QUE QUIERES EDITAR ESTE DESCANSO?');" oninput="actualizarTiempo()">
            <!-- Campos de entrada -->
            <input type="hidden" name="cod_descanso" value="<?php echo $row['cod_descanso']; ?>">
            <label for="nom_descanso">NOMBRE:</label>
            <input type="text" id="nom_descanso" name="nom_descanso" value="<?php echo $row['nom_descanso']; ?>" required>
            <label for="sede" class="front-label">SEDE:</label>
            <select id="sede" name="sede" required>
                <option value="">SELECCIONE LA SEDE</option>
                <?php
                // Obtener las opciones disponibles para el campo "SEDE" de la tabla "sedes"
                $sede_options_query = "SELECT sede FROM sedes";
                $sede_options_result = $conn->query($sede_options_query);

                // Iterar sobre las opciones y marcar como seleccionada la que coincide
                while ($sede_option_row = $sede_options_result->fetch_assoc()) {
                    $selected = ($sede_option_row['sede'] == $row['sede']) ? 'selected' : '';
                    echo "<option value='" . $sede_option_row['sede'] . "' $selected>" . $sede_option_row['sede'] . "</option>";
                }
                ?>
            </select>
            <label for="ini_descanso">HORA DE INICIO:</label>
            <input type="time" id="ini_descanso" name="ini_descanso" value="<?php echo $row['ini_descanso']; ?>" required onchange="actualizarTiempo()">
            <label for="fin_descanso">HORA FIN:</label>
            <input type="time" id="fin_descanso" name="fin_descanso" value="<?php echo $row['fin_descanso']; ?>" required onchange="actualizarTiempo()">
            <label class="front-label" for="tiempo_des">TIEMPO DE DESCANSO:</label>
            <input type="text" id="tiempo_des" name="tiempo_des" value="<?php echo $row['tiempo_des']; ?>" placeholder="AQUÍ SE MOSTRARÁ EL TIEMPO TRANSCURRIDO." readonly>
            <button type="submit">ACTUALIZAR</button>
        </form>

        <!-- JavaScript -->
        <script>
        // Función para calcular la diferencia de tiempo
function actualizarTiempo() {
    var horaInicio = document.getElementById('ini_descanso').value;
    var horaFin = document.getElementById('fin_descanso').value;

    // Validar que se ingresen ambas horas
    if (horaInicio && horaFin) {
        // Convertir las horas a objetos Date
        var inicio = new Date('2000-01-01T' + horaInicio + ':00');
        var fin = new Date('2000-01-01T' + horaFin + ':00');

        // Calcular la diferencia de tiempo en milisegundos
        var diferencia = fin - inicio;

        // Convertir la diferencia a horas, minutos y segundos
        var horas = Math.floor(diferencia / 3600000);
        diferencia %= 3600000;
        var minutos = Math.floor(diferencia / 60000);
        diferencia %= 60000;
        var segundos = Math.floor(diferencia / 1000);

        // Mostrar el resultado en el placeholder
        document.getElementById('tiempo_des').value = horas + ':' + minutos + ':' + segundos;
    } else {
        document.getElementById('tiempo_des').value = ''; // Limpiar el campo si falta alguna hora
    }
}
        </script>

        </body>
        </html>

        <?php
    } else {
        echo "NO SE ENCONTRÓ NINGÚN DESCANSO CON EL CÓDIGO $codigoDescanso.";
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "NO SE RECIBIÓ EL CÓDIGO DEL DESCANSO A EDITAR.";
}
?>
