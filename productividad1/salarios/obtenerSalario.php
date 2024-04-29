<?php
// Verificar si se ha recibido un ID válido
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "datosgenerales";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Escapar el ID para evitar inyección de SQL
    $id = $conn->real_escape_string($_GET['id']);

    // Consulta SQL para obtener los datos del salario con el ID proporcionado
    $sql = "SELECT * FROM salarios WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Convertir los datos del salario a formato JSON
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array("error" => "No se encontraron datos para el ID proporcionado."));
    }

    $conn->close();
} else {
    echo json_encode(array("error" => "ID no válido."));
}
?>
