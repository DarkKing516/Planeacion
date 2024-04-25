<?php
// Verificar si se recibió el parámetro de artículo
if(isset($_POST['cod_articulo'])) {
    // Obtener el código de artículo recibido por POST
    $cod_articulo = $_POST['cod_articulo'];

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
    $sql_estandar_relacionado = "SELECT nombre_estandar, t_estandar FROM estandar WHERE cod_articulo = ?";
    
    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare($sql_estandar_relacionado);
    $stmt->bind_param("s", $cod_articulo);
    $stmt->execute();

    // Obtener resultados
    $result_estandar_relacionado = $stmt->get_result();

    // Inicializar un array para almacenar los estándares
    $estandares = array();

    // Procesar los resultados
    if ($result_estandar_relacionado->num_rows > 0) {
        // Recorrer los resultados y agregar los estándares al array
        while ($row = $result_estandar_relacionado->fetch_assoc()) {
            // Agregar tanto el nombre_estandar como el t_estandar al array
            $estandares[] = array(
                "nombre_estandar" => $row["nombre_estandar"],
                "t_estandar" => $row["t_estandar"]
            );
        }
    }

    // Devolver los estándares en formato JSON
    echo json_encode($estandares);

    // Cerrar la consulta preparada
    $stmt->close();

    // Cerrar conexión a la base de datos
    $conn->close();
} else {
    echo "No se recibió el parámetro de artículo.";
}
?>
