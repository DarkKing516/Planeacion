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

// Realizar una consulta SQL para obtener los datos de producción
$sql = "SELECT * FROM produccion"; // Reemplaza 'tabla_de_produccion' con el nombre de tu tabla
$resultado = $conn->query($sql);

// Configuración de encabezados para UTF-8 y Excel
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename=INFORME_PRODUCCION.xls');
header('Pragma: no-cache');
header('Expires: 0');

// BOM para UTF-8
echo "\xEF\xBB\xBF";

// Inicio de la tabla Excel
echo '<table border="1">';
echo '<tr>';
echo '<th colspan="4">INFORME PRODUCCIÓN</th>';
echo '</tr>';
echo '<tr><th>SEDE</th><th>USUARIO</th><th>FECHA</th><th>CEDULA</th><th>EMPLEADO</th><th>COD. GRUPO</th><th>GRUPO</th><th>COD. ARTÍCULO</th><th>ARTÍCULO</th><th>COD. PROCESO</th><th>PROCESO</th><th>ESTÁNDAR</th><th>T. ESTÁNDAR</th><th>H. INICIO</th><th>H. FIN</th><th>T. LABORADO</th><th>D. 1</th><th>D. 2</th><th>ALMUERZO</th><th>T. REAL</th><th>H. EXTRA</th><th>EQ. DISPONIBLE</th><th>EQ.TALLER</th><th>OBSERVACIÓN</th></tr>';

// Iterar sobre los resultados y generar las filas de la tabla
while ($row = $resultado->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . $row["sede"] . '</td>';
    echo '<td>' . $row["usuario"] . '</td>';
    echo '<td>' . $row["fecha"] . '</td>';
    echo '<td>' . $row["cedula"] . '</td>';
    echo '<td>' . $row["empleado"] . '</td>';
    echo '<td>' . $row["cod_grupo"] . '</td>';
    echo '<td>' . $row["grupo"] . '</td>';
    echo '<td>' . $row["cod_articulo"] . '</td>';
    echo '<td>' . $row["articulo"] . '</td>';
    echo '<td>' . $row["cod_proceso"] . '</td>';
    echo '<td>' . $row["proceso"] . '</td>';
    echo '<td>' . $row["nombre_estandar"] . '</td>';
    echo '<td>' . $row["t_estandar"] . '</td>';
    echo '<td>' . $row["hora_inicio"] . '</td>';
    echo '<td>' . $row["hora_fin"] . '</td>';
    echo '<td>' . $row["tiempoLaborado"] . '</td>';
    echo '<td>' . $row["descanso1"] . '</td>';
    echo '<td>' . $row["descanso2"] . '</td>';
    echo '<td>' . $row["almuerzo"] . '</td>';
    echo '<td>' . $row["tiempoReal"] . '</td>';
    echo '<td>' . $row["horasExtra"] . '</td>';
    echo '<td>' . $row["eq_disponible"] . '</td>';
    echo '<td>' . $row["eq_taller"] . '</td>';
    echo '<td>' . $row["observaciones"] . '</td>';
    echo '</tr>';
}

// Fin de la tabla Excel
echo '</table>';

// Cerrar la conexión
$conn->close();
?>
