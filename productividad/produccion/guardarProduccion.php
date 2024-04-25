<?php
// Iniciar sesión
session_start();

// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $sede = $_POST['sede'];
    $usuario = $_SESSION['usuario'];
    $fecha = $_POST['fecha'];
    $cedula = $_POST['cedula'];
    $empleado = $_POST['empleado'];
    $cod_grupo = $_POST['cod_grupo'];
    $grupo = $_POST['grupo'];
    $cod_articulo = $_POST['cod_articulo'];
    $articulo = $_POST['articulo'];
    $t_estandar = $_POST['t_estandar'];
    $nombre_estandar = $_POST['nombre_estandar'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $tiempoLaborado = $_POST['tiempoLaborado'];
    $descanso1 = isset($_POST['descanso1']) ? 'SI' : 'NO';
    $descanso2 = isset($_POST['descanso2']) ? 'SI' : 'NO';
    $almuerzo = isset($_POST['almuerzo']) ? 'SI' : 'NO';
    $tiempoReal = $_POST['tiempoReal'];
    $horasExtra = $_POST['horasExtra'];
    $eq_disponible = $_POST['eq_disponible'];
    $eq_taller = $_POST['eq_taller'];
    $observaciones = $_POST['observaciones'];

    // Aquí puedes realizar la conexión a tu base de datos y guardar los datos recibidos
    // Reemplaza los valores de conexión con los de tu configuración
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "datosgenerales";

    // Crear conexión
    $conn = new mysqli($server, $user, $pass, $db);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("CONEXIÓN FALLIDA: " . $conn->connect_error);
    }

    // Consulta para verificar si existen registros para el empleado en la misma fecha
    $sql_verificar = "SELECT SUM(tiempoReal) AS total_tiempo FROM produccion WHERE cedula = '$cedula' AND fecha = '$fecha'";
    $result_verificar = $conn->query($sql_verificar);

    if ($result_verificar->num_rows > 0) {
        $row_verificar = $result_verificar->fetch_assoc();
        $total_tiempo = $row_verificar['total_tiempo'];

        // Verificar si el tiempo total está dentro del rango permitido
        if ($total_tiempo !== null && ($total_tiempo + $tiempoReal) < 510 || ($total_tiempo + $tiempoReal) > 900) {
            $mensaje = ($total_tiempo + $tiempoReal) < 510 ? "ESTÁS INGRESANDO UN REGISTRO CON MENOS DEL TIEMPO PERMITIDO, EL TIEMPO MÍNIMO PERMITIDO ES DE 510 MINUTOS. ¿DESEA GUARDAR DE ESTA MANERA ESTE REGISTRO?" : "ESTE EMPLEADO YA TIENE UN REGISTRO EN ESTA FECHA Y EL TIEMPO MÁXIMO PERMITIDO ES DE 900 MINUTOS, ¿IGUALMENTE DESEA GUARDAR ESTE REGISTRO?";
            
            // Mostrar cuadro de confirmación al usuario
            echo "<script>
                    if (confirm('$mensaje')) {
                        // Si el usuario acepta, enviar el formulario para guardar los datos
                        document.getElementById('guardarForm').submit();
                    } else {
                        // Si el usuario cancela, redireccionar a la página anterior
                        window.location.href = 'produccion.php';
                    }
                  </script>";
        } else {
            // Preparar la consulta SQL para insertar los datos en la tabla correspondiente
            $sql = "INSERT INTO produccion (sede, usuario, fecha, cedula, empleado, cod_grupo, grupo, cod_articulo, articulo, t_estandar, nombre_estandar, hora_inicio, hora_fin, tiempoLaborado, descanso1, descanso2, almuerzo, tiempoReal, horasExtra, eq_disponible, eq_taller, observaciones)
                    VALUES ('$sede', '$usuario', '$fecha', '$cedula', '$empleado', '$cod_grupo', '$grupo', '$cod_articulo', '$articulo', '$t_estandar',  '$nombre_estandar', '$hora_inicio', '$hora_fin', '$tiempoLaborado', '$descanso1', '$descanso2', '$almuerzo', '$tiempoReal', '$horasExtra', '$eq_disponible', '$eq_taller', '$observaciones')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('DATOS GUARDADOS CORRECTAMENTE');</script>";
                echo "<script>window.location.href = 'produccion.php';</script>";
            } else {
                echo "ERROR: " . $sql . "<br>" . $conn->error;
            }
        }
    } else {
        // No existen registros previos para el empleado en esta fecha, continuar con el proceso de guardado
        // ...
    }

    // Cerrar conexión
    $conn->close();
} else {
    echo "ERROR: NO SE RECIBIERON LOS DATOS EN EL FORMULARIO";
}
?>
