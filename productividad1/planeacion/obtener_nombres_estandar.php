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

    // Consulta para obtener los nombres estándar por grupo desde la tabla estandar
    $sql_nombres = "SELECT DISTINCT nombre_estandar FROM estandar WHERE grupo = ?";
    
    // Preparar la consulta
    $stmt = $conn->prepare($sql_nombres);
    $stmt->bind_param("s", $grupo); // Ligamos el parámetro

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener resultado
    $result_nombres = $stmt->get_result();

    // Inicializar un array para almacenar los nombres estándar
    $nombres = array();

    if ($result_nombres->num_rows > 0) {
        // Recorrer los resultados y agregar los nombres estándar al array
        while ($row = $result_nombres->fetch_assoc()) {
            $nombres[] = $row["nombre_estandar"];
        }
    }

    // Devolver los nombres estándar en formato JSON
    echo json_encode($nombres);
    
    // Cerrar conexión a la base de datos
    $conn->close();
} else {
    echo "No se recibió el parámetro de grupo.";
}
?>
