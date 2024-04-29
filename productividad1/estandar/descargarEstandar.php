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
$proceso = $_GET['proceso'] ?? '';
$grupo = $_GET['grupo'] ?? '';

// Consulta SQL para obtener los registros de la tabla 'estandar'
$sql = "SELECT * FROM estandar";
if (!empty($proceso)) {
    $sql .= " WHERE proceso = '$proceso'";
    if (!empty($grupo)) {
        $sql .= " AND grupo = '$grupo'";
    }
} elseif (!empty($grupo)) {
    $sql .= " WHERE grupo = '$grupo'";
}

$resultado = $conn->query($sql);

// Configuración de encabezados para UTF-8
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename=INFORME ESTÁNDAR.xls');
header('Pragma: no-cache');
header('Expires: 0');

// BOM para UTF-8
echo "\xEF\xBB\xBF";

// Inicio de la tabla Excel
echo '<table border="1">';
echo '<tr>';
echo '<th colspan="4">INFORME ESTANDAR</th>';
echo '</tr>';
echo '<tr><th>COD. GRUPO</th><th>GRUPO</th><th>COD. PROCESO</th><th>PROCESO</th><th>COD. ARTÍCULO</th><th>ARTÍCULO</th><th>COD. ESTÁNDAR</th><th>ESTÁNDAR</th><th>T. ESTÁNDAR</th></tr>';

// Iterar sobre los resultados y generar las filas de la tabla
while ($row = $resultado->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . $row['cod_grupo'] . '</td>';
    echo '<td>' . $row['grupo'] . '</td>';
    echo '<td>' . $row['cod_proceso'] . '</td>';
    echo '<td>' . $row['proceso'] . '</td>';
    echo '<td>' . $row['cod_articulo'] . '</td>';
    echo '<td>' . $row['articulo'] . '</td>';
    echo '<td>' . $row['peso'] . '</td>';
    echo '<td>' . $row['cod_estandar'] . '</td>';
    echo '<td>' . $row['nombre_estandar'] . '</td>';
    echo '<td>' . $row['t_estandar'] . '</td>';
    echo '</tr>';
}

// Fin de la tabla Excel
echo '</table>';

// Cerrar la conexión
$conn->close();
?>
