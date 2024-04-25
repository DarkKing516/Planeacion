<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datosgenerales";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se recibió un código de artículo válido
if (!isset($_POST['codigoArticulo']) || !is_numeric($_POST['codigoArticulo'])) {
    die("Código de artículo no válido");
}

// Obtener el código de artículo enviado por POST
$codigoArticulo = $_POST['codigoArticulo'];

// Consulta SQL para obtener los procesos relacionados con el artículo seleccionado
$sql = "SELECT cod_proceso, proceso FROM estandar WHERE cod_articulo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $codigoArticulo); // "i" indica que el valor es un entero
$stmt->execute();
$result = $stmt->get_result();

// Array para almacenar los datos de los procesos
$procesos = array();

// Obtener y almacenar los datos de los procesos
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $procesos[] = array(
            'cod_proceso' => $row['cod_proceso'],
            'proceso' => $row['proceso']
        );
    }
}

// Enviar los datos de los procesos como respuesta en formato JSON
echo json_encode($procesos);

// Cerrar el statement y la conexión
$stmt->close();
$conn->close();
?>
