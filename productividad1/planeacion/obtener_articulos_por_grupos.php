<?php
// Verificar si se recibió el parámetro de grupo
if (isset($_GET['grupo'])) {
    $grupo = $_GET['grupo'];

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

    // Consulta para obtener los artículos por grupo desde la tabla estandar
    $sql_articulos = "SELECT DISTINCT cod_articulo, articulo FROM estandar WHERE grupo = ?";

    // Preparar la consulta
    $stmt = $conn->prepare($sql_articulos);
    $stmt->bind_param("s", $grupo); // Ligamos el parámetro

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener resultado
    $result_articulos = $stmt->get_result();

    // Inicializar un array para almacenar los artículos
    $articulos = array();

    if ($result_articulos->num_rows > 0) {
        // Recorrer los resultados y agregar los artículos al array
        while ($row = $result_articulos->fetch_assoc()) {
            // Agregar el código y el nombre del artículo como un array asociativo
            $articulos[] = array(
                "codigo" => $row["cod_articulo"],
                "nombre" => $row["articulo"]
            );
        }
    }

    // Devolver los artículos en formato JSON
    echo json_encode($articulos);

    // Cerrar conexión a la base de datos
    $conn->close();
} else {
    echo "No se recibió el parámetro de grupo.";
}
?>
