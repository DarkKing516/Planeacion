<!-- <?php
        // Verificar si se recibieron los datos del formulario
        if (isset($_POST['sede']) && isset($_POST['usuario']) && isset($_POST['fecha']) && isset($_POST['cedula']) && isset($_POST['nombre']) && isset($_POST['indice']) && isset($_POST['cod_articulo']) && isset($_POST['t_estandar']) && isset($_POST['peso_articulo']) && isset($_POST['horas_dia']) && isset($_POST['minutos_dia']) && isset($_POST['tarea_dia']) && isset($_POST['peso_total']) && isset($_POST['observacion']) && isset($_POST['grupo']) && isset($_POST['articulo'])) {

            // Conexión a la base de datos (reemplaza los valores con los de tu configuración)
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "datosgenerales";

            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verificar la conexión
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            // Preparar la consulta SQL para inserción
            $sql = "INSERT INTO planeacion (sede, usuario, fecha, cedula, nombre, cod_articulo, t_estandar, peso_articulo, horas_dia, minutos_dia, tarea_dia, peso_total, observacion, grupo, articulo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Preparar la declaración
            $stmt = $conn->prepare($sql);
            $indice = $_POST['indice'];
            // Verificar si la preparación de la declaración fue exitosa
            if ($stmt) {
                // Vincular parámetros y ejecutar la declaración dentro de un bucle
                for ($i = $indice; $i < count($_POST['cedula']); $i++) {
                    // Vincular parámetros
                    $stmt->bind_param("ssssssssssssssss", $_POST['sede'], $_POST['usuario'], $_POST['fecha'], $_POST['cedula'][$i], $_POST['nombre'][$i], $_POST['cod_articulo'], $_POST['t_estandar'], $_POST['peso_articulo'], $_POST['horas_dia'][$i], $_POST['minutos_dia'][$i], $_POST['tarea_dia'][$i], $_POST['peso_total'][$i], $_POST['observacion'][$i], $_POST['grupo'][$i], $_POST['articulo'][$i]);

                    $stmt->execute();
                }

                $stmt->close();

                // Cerrar la conexión
                $conn->close();

                // Redirigir o mostrar un mensaje de éxito
                echo "Los datos se han guardado correctamente.";
            } else {
                // Si la preparación de la declaración falla, mostrar un mensaje de error
                echo "Error al preparar la declaración SQL: " . $conn->error;
            }
        } else {
            // Si no se recibieron todos los datos del formulario, mostrar un mensaje de error
            echo "No se recibieron todos los datos del formulario.";
            echo $_POST['sede'];
            echo $_POST['usuario'];
            echo $_POST['fecha'];
            echo $indice = $_POST['indice'];
            echo $_POST['cedula'][$indice];
            echo $_POST['nombre'][$indice];
            echo $_POST['horas_dia'][$indice];
            echo $_POST['minutos_dia'][$indice];
            echo $_POST['peso_total'][$indice];
            echo $_POST['observacion'][$indice];
            // echo $_POST['cod_articulo'];
            // echo $_POST['articulo'];
            // echo $_POST['nombre_estandar'];
            // echo $_POST['t_estandar'];
            // echo $_POST['peso_articulo'];
            // echo $_POST['tarea_dia'][$indice];
            // echo $_POST['grupo'];
        }
        ?> -->

<?php
// Verificar si se recibieron los datos del formulario
if (isset($_POST['sede']) && isset($_POST['usuario']) && isset($_POST['fecha']) && isset($_POST['indice']) && isset($_POST['cedula']) && isset($_POST['nombre']) && isset($_POST['horas_dia']) && isset($_POST['minutos_dia']) && isset($_POST['peso_total']) && isset($_POST['observacion'])) {

    // Conexión a la base de datos (reemplaza los valores con los de tu configuración)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "datosgenerales";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Preparar la consulta SQL para inserción
    $sql = "INSERT INTO planeacion (sede, usuario, fecha, cedula, nombre, horas_dia, minutos_dia, peso_total, observacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la declaración
    $stmt = $conn->prepare($sql);
    $indice = $_POST['indice'];
    // Verificar si la preparación de la declaración fue exitosa
    if ($stmt) {
        // Vincular parámetros y ejecutar la declaración dentro de un bucle
        // Vincular parámetros
        $stmt->bind_param("sssssssss", $_POST['sede'], $_POST['usuario'], $_POST['fecha'], $_POST['cedula'][$indice], $_POST['nombre'][$indice], $_POST['horas_dia'][$indice], $_POST['minutos_dia'][$indice], $_POST['peso_total'][$indice], $_POST['observacion'][$indice]);

        $stmt->execute();

        $stmt->close();

        // Cerrar la conexión
        $conn->close();

        // Redirigir o mostrar un mensaje de éxito
        echo "Los datos se han guardado correctamente.";
        header("Location: ./planeacion.php");
    } else {
        // Si la preparación de la declaración falla, mostrar un mensaje de error
        echo "Error al preparar la declaración SQL: " . $conn->error;
    }
} else {
    // Si no se recibieron todos los datos del formulario, mostrar un mensaje de error
    echo "No se recibieron todos los datos del formulario.";
}
?>