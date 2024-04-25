<?php
// Verificar si se recibió el parámetro de artículo
if (isset($_GET['articulo'])) {
    $articulo = $_GET['articulo'];

    // Configurar la conexión a la base de datos (ajusta los valores según tu configuración)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "datosgenerales";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta para obtener el peso del artículo desde la tabla estandar
    $sql_peso = "SELECT peso FROM estandar WHERE articulo = ?";

    // Preparar la consulta
    $stmt = $conn->prepare($sql_peso);
    $stmt->bind_param("s", $articulo); // Ligamos el parámetro

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener resultado
    $result_peso = $stmt->get_result();

    // Verificar si se encontró el artículo
    if ($result_peso->num_rows > 0) {
        // Obtener el resultado como un array asociativo
        $row = $result_peso->fetch_assoc();
        
        // Obtener el peso del artículo
        $peso = array("peso" => $row["peso"]);

        // Devolver el peso del artículo en formato JSON
        echo json_encode($peso);
    } else {
        // Si no se encontró el artículo, devolver un mensaje de error
        echo json_encode(array("error" => "No se encontró el peso para el artículo seleccionado."));
    }

    // Cerrar conexión a la base de datos
    $conn->close();
} else {
    // Si no se recibió el parámetro de artículo, devolver un mensaje de error
    echo json_encode(array("error" => "No se recibió el parámetro de artículo."));
}
?>
