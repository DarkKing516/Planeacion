<?php
// Iniciar sesión (si aún no se ha iniciado)
session_start();

// Verificar si hay una sesión activa para el usuario
if (!isset($_SESSION['usuario'])) {
    // Devolver un mensaje de error y finalizar la ejecución del script
    echo "ERROR: NO SE HA INICIADO SESIÓN";
    exit();
}

// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establecer la conexión a la base de datos MySQL
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "datosgenerales";

    // Crear la conexión
    $conn = new mysqli($server, $user, $pass, $db);

    // Verificar la conexión
    if ($conn->connect_error) {
        // Devolver un mensaje de error y finalizar la ejecución del script
        echo "ERROR DE CONEXIÓN A LA BASE DE DATOS";
        exit();
    }

    // Obtener los datos enviados desde el formulario
    $codigo = $_POST["cod_descanso"];
    $nombre = $_POST["nom_descanso"];
    $sede = $_POST["sede"];
    $inicio = $_POST["ini_descanso"];
    $fin = $_POST["fin_descanso"];
    $tiempo = $_POST["tiempo_des"];
    
    // Insertar el nuevo descanso en la base de datos
    $sql = "INSERT INTO descansos (cod_descanso, nom_descanso, sede, ini_descanso, fin_descanso, tiempo_des) VALUES ('$codigo', '$nombre', '$sede', '$inicio', '$fin', '$tiempo')";
    if ($conn->query($sql) === TRUE) {
        // Si se guardó correctamente, redirigir a descansos.php con un parámetro de éxito
        header("Location: descansos.php?status=success");
        exit(); // Importante: detener la ejecución del script después de redirigir
    } else {
        // Si ocurrió un error, redirigir a descansos.php con un parámetro de error
        header("Location: descansos.php?status=error");
        exit(); // Importante: detener la ejecución del script después de redirigir
    }

    // Cerrar la conexión
    $conn->close();
} else {
    // Si no se han enviado los datos del formulario, devolver un mensaje de error
    echo "NO SE HAN RECIBIDO DATOS DEL FORMULARIO";
}
?>
