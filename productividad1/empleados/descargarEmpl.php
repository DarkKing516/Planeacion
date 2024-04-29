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
$estado = $_GET['estado'] ?? '';
$cargo = $_GET['cargo'] ?? '';
$sede = $_GET['sede'] ?? '';

// Consulta SQL para obtener los registros de la tabla 'empleados'
$sql = "SELECT * FROM empleados WHERE 1=1";
if (!empty($estado)) {
    $sql .= " AND estado = '$estado'";
}
if (!empty($cargo)) {
    $sql .= " AND cargo = '$cargo'";
}
if (!empty($sede)) {
    $sql .= " AND sede = '$sede'";
}

$resultado = $conn->query($sql);

// Configuración de encabezados para UTF-8
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename=INFORME EMPLEADOS.xls');
header('Pragma: no-cache');
header('Expires: 0');

// BOM para UTF-8
echo "\xEF\xBB\xBF";

// Inicio de la tabla Excel
echo '<table border="1">';
echo '<tr>';
echo '<th colspan="4">INFORME EMPLEADOS</th>';
echo '</tr>';
echo '<tr><th>ESTADO</th><th>CÉDULA</th><th>NOMBRE</th><th>CARGO</th><th>SEDE</th><th>FECHA</th><th>BÁSICO</th><th>V / HORA</th></tr>';

// Iterar sobre los resultados y generar las filas de la tabla
while ($row = $resultado->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . $row['estado'] . '</td>';
    echo '<td>' . $row['cedula'] . '</td>';
    echo '<td>' . $row['nombre'] . '</td>';
    echo '<td>' . $row['cargo'] . '</td>';
    echo '<td>' . $row['sede'] . '</td>';
    echo '<td>' . $row['fecha'] . '</td>';
    echo '<td>' . $row['basico'] . '</td>';
    echo '<td>' . $row['vHora'] . '</td>';
    echo '</tr>';
}

// Fin de la tabla Excel
echo '</table>';

// Cerrar la conexión
$conn->close();
?>
