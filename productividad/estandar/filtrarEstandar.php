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

// Obtener los valores de tipo y grupo enviados por la solicitud AJAX
$tipo = $_GET['proceso'];
$grupo = $_GET['grupo'];

// Construir la consulta SQL basada en los valores de tipo y grupo
$sql = "SELECT * FROM estandar";
if (!empty($tipo) && !empty($grupo)) {
    $sql .= " WHERE proceso = '$tipo' AND grupo = '$grupo'";
} elseif (!empty($tipo)) {
    $sql .= " WHERE proceso = '$tipo'";
} elseif (!empty($grupo)) {
    $sql .= " WHERE grupo = '$grupo'";
}

// Ejecutar la consulta SQL
$resultado = $conn->query($sql);

// Construir la tabla HTML con los resultados de la consulta
$table = "<table id='tabla-estandar'>";
$table .= "<tr><th>CÓD. GRUPO</th><th>GRUPO</th><th>COD. PROCESO</th><th>PROCESO</th><th>COD. ARTÍCULO</th><th>ARTÍCULO</th><th>PESO</th><th>ESTÁNDAR</th><th>T. ESTÁNDAR</th></tr>";
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $table .= "<tr>";
        $table .= "<td>" . $fila["cod_grupo"] . "</td>";
        $table .= "<td>" . $fila["grupo"] . "</td>";
        $table .= "<td>" . $fila["cod_proceso"] . "</td>";
        $table .= "<td>" . $fila["proceso"] . "</td>";
        $table .= "<td>" . $fila["cod_articulo"] . "</td>";
        $table .= "<td>" . $fila["articulo"] . "</td>";
        $table .= "<td>" . $fila["peso"] . "</td>";
        $table .= "<td>" . $fila["nombre_estandar"] . "</td>";
        $table .= "<td>" . $fila["t_estandar"] . "</td>";
        $table .= "</tr>";
    }
}
$table .= "</table>";

echo $table;

$conn->close();
?>
