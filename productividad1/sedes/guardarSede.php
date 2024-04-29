<?php
// Verificar si se recibieron los datos del formulario
if(isset($_POST['sede']) && isset($_POST['per_autorizado'])) {
    // Obtener los datos del formulario
    $nombreSede = $_POST['sede'];
    $perAutorizado = $_POST['per_autorizado'];

    // Conexión a la base de datos
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "datosgenerales";

    // Crear conexión a la base de datos
    $conn = new mysqli($server, $user, $pass, $db);
    // Verificar la conexión
    if ($conn->connect_error) {
        die("CONEXIÓN FALLIDA: " . $conn->connect_error);
    }

    // Preparar la consulta SQL utilizando una sentencia preparada
    $sql = "INSERT INTO sedes (sede, per_autorizado) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("ERROR AL PREPARAR LA CONSULTA: " . $conn->error);
    }

    // Vincular los parámetros
    $stmt->bind_param("ss", $nombreSede, $perAutorizado);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $response = "LA SEDE SE HA GUARDADO CORRECTAMENTE.";
    } else {
        $response = "ERROR AL GUARDAR LA SEDE: " . $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();

    // Devolver respuesta
    echo $response;
} else {
    echo "NO SE RECIBIERON LOS DATOS NECESARIOS PARA GUARDAR LA SEDE.";
}
?>
