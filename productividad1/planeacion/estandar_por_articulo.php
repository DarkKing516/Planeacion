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

    // Consulta para obtener los estándares relacionados con el artículo desde la tabla estandar
    $sql_estandares = "SELECT nombre_estandar, t_estandar FROM estandar WHERE articulo = ?";

    // Preparar la consulta
    $stmt = $conn->prepare($sql_estandares);
    $stmt->bind_param("s", $articulo); // Ligamos el parámetro

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener resultado
    $result_estandares = $stmt->get_result();

    // Inicializar un array para almacenar los estándares
    $estandares = array();

    if ($result_estandares->num_rows > 0) {
        // Recorrer los resultados y agregar los estándares al array
        while ($row = $result_estandares->fetch_assoc()) {
            // Agregar tanto el nombre_estandar como el t_estandar al array
            $estandares[] = array(
                "nombre_estandar" => $row["nombre_estandar"],
                "t_estandar" => $row["t_estandar"]
            );
        }
    }

    // Devolver los estándares en formato JSON
    echo json_encode($estandares);

    // Cerrar conexión a la base de datos
    $conn->close();
} else {
    echo "No se recibió el parámetro de artículo.";
}
?>
