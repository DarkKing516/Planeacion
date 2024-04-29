<?php
// Verificar si se recibió el id de la sede
if(isset($_POST['id'])) {
    // Conexión a la base de datos
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "datosgenerales";

    $conn = new mysqli($server, $user, $pass, $db);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("ERROR DE CONEXION: " . $conn->connect_error);
    }

    // Preparar la consulta SQL para obtener el nombre y el número de personal autorizado de la sede
    $idSede = $_POST['id'];
    $sql = "SELECT sede, per_autorizado FROM sedes WHERE id = $idSede";

    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Verificar si se encontró la sede
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nombreSede = $row["sede"];
        $perAutorizado = $row["per_autorizado"];
        $sedeDetails = array("sede" => $nombreSede, "per_autorizado" => $perAutorizado);
        echo json_encode($sedeDetails); // Devolver los detalles de la sede como respuesta en formato JSON
    } else {
        echo "NO SE ENCONTRÓ LA SEDE.";
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "NO SE RECIBIÓ EL ID DE LA SEDE.";
}
?>
