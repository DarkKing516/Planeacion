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

// Obtener el código de grupo enviado por POST
$codigoGrupo = $_POST['codigoGrupo'];

// Consulta SQL para obtener los artículos según el grupo seleccionado
$sql = "SELECT DISTINCT cod_articulo, articulo FROM estandar WHERE cod_grupo = $codigoGrupo";
$result = $conn->query($sql);

// Array para almacenar los datos de los artículos
$articulos = array();

// Obtener y almacenar los datos de los artículos
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $articulos[] = array(
            'cod_articulo' => $row['cod_articulo'],
            'articulo' => $row['articulo']
        );
    }
}

// Enviar los datos de los artículos como respuesta en formato JSON
echo json_encode($articulos);

// Cerrar la conexión
$conn->close();
?>
