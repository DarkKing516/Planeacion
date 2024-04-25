<?php
// Configuración de la conexión a la base de datos
$server = "localhost";
$user = "root";
$pass = "";
$db = "datosgenerales";

// Conexión a la base de datos
$conn = new mysqli($server, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("La conexión falló: " . $conn->connect_error);
}

// Obtener los datos filtrados de la base de datos
$tipo = $_GET['tipo'] ?? '';
$grupo = $_GET['grupo'] ?? '';

// Consulta SQL para obtener los registros de la tabla 'articulos'
$sql = "SELECT * FROM articulos";
if (!empty($tipo)) {
    $sql .= " WHERE tipo = '$tipo'";
    if (!empty($grupo)) {
        $sql .= " AND grupo = '$grupo'";
    }
} elseif (!empty($grupo)) {
    $sql .= " WHERE grupo = '$grupo'";
}

$resultado = $conn->query($sql);

// Configuración de encabezados para UTF-8
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename=INFORME ARTÍCULOS.xls');
header('Pragma: no-cache');
header('Expires: 0');

// BOM para UTF-8
echo "\xEF\xBB\xBF";

// Inicio de la tabla Excel
echo '<table border="1">';
echo '<tr>';
echo '<th colspan="4">INFORME ARTÍCULOS</th>';
echo '</tr>';
echo '<tr><th>CÓDIGO</th><th>ARTÍCULO</th><th>GRUPO</th><th>PESO</th><th>VALOR REPO</th><th>CÓD. ALTERNO</th><th>TIPO</th><th>ESTADO</th></tr>';

// Iterar sobre los resultados y generar las filas de la tabla
while ($row = $resultado->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . $row['codigo'] . '</td>';
    echo '<td>' . $row['articulo'] . '</td>';
    echo '<td>' . $row['grupo'] . '</td>';
    echo '<td>' . $row['peso'] . '</td>';
    echo '<td>' . $row['valorRepo'] . '</td>';
    echo '<td>' . $row['codAlterno'] . '</td>';
    echo '<td>' . $row['tipo'] . '</td>';
    echo '<td>' . $row['estado'] . '</td>';
    echo '</tr>';
}

// Fin de la tabla Excel
echo '</table>';

// Cerrar la conexión
$conn->close();
?>
