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
    die("LA CONEXIÓN FALLÓ: " . $conn->connect_error);
}

// Construir la consulta SQL basada en los valores de tipo y grupo
$sql = "SELECT * FROM produccion";

// Ejecutar la consulta SQL
$resultado = $conn->query($sql);

// Construir la tabla HTML con los resultados de la consulta
$table = "<table id='tabla-produccion'>";
$table .= "<tr><th>SEDE</th><th>USUARIO</th><th>FECHA</th><th>CEDULA</th><th>EMPLEADO</th><th>COD. GRUPO</th><th>GRUPO</th><th>COD. ARTÍCULO</th><th>ARTÍCULO</th><th>COD. PROCESO</th><th>PROCESO</th><th>ESTÁNDAR</th><th>T. ESTÁNDAR</th><th>H. INICIO</th><th>H. FIN</th><th>T. LABORADO</th><th>D. 1</th><th>D. 2</th><th>ALMUERZO</th><th>T. REAL</th><th>H. EXTRA</th><th>EQ. DISPONIBLE</th><th>EQ.TALLER</th><th>OBSERVACIÓN</th></tr>";
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $table .= "<tr>";
        $table .= "<td>" . $fila["sede"] . "</td>";
        $table .= "<td>" . $fila["usuario"] . "</td>";
        $table .= "<td>" . $fila["fecha"] . "</td>";
        $table .= "<td>" . $fila["cedula"] . "</td>";
        $table .= "<td>" . $fila["empleado"] . "</td>";
        $table .= "<td>" . $fila["cod_grupo"] . "</td>";
        $table .= "<td>" . $fila["grupo"] . "</td>";
        $table .= "<td>" . $fila["cod_articulo"] . "</td>";
        $table .= "<td>" . $fila["articulo"] . "</td>";
        $table .= "<td>" . $fila["cod_proceso"] . "</td>";
        $table .= "<td>" . $fila["proceso"] . "</td>";
        $table .= "<td>" . $fila["nombre_estandar"] . "</td>";
        $table .= "<td>" . $fila["t_estandar"] . "</td>";
        $table .= "<td>" . $fila["hora_inicio"] . "</td>";
        $table .= "<td>" . $fila["hora_fin"] . "</td>";
        $table .= "<td>" . $fila["tiempoLaborado"] . "</td>";
        $table .= "<td>" . $fila["descanso1"] . "</td>";
        $table .= "<td>" . $fila["descanso2"] . "</td>";
        $table .= "<td>" . $fila["almuerzo"] . "</td>";
        $table .= "<td>" . $fila["tiempoReal"] . "</td>";
        $table .= "<td>" . $fila["horasExtra"] . "</td>";
        $table .= "<td>" . $fila["eq_disponible"] . "</td>";
        $table .= "<td>" . $fila["eq_taller"] . "</td>";
        $table .= "<td>" . $fila["observaciones"] . "</td>";
        $table .= "</tr>";
    }
}
$table .= "</table>";

echo $table;

$conn->close();
?>
