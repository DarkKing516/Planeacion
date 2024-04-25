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
$tipo = $_GET['tipo'];
$grupo = $_GET['grupo'];

// Construir la consulta SQL basada en los valores de tipo y grupo
$sql = "SELECT * FROM articulos";
if (!empty($tipo) && !empty($grupo)) {
    $sql .= " WHERE tipo = '$tipo' AND grupo = '$grupo'";
} elseif (!empty($tipo)) {
    $sql .= " WHERE tipo = '$tipo'";
} elseif (!empty($grupo)) {
    $sql .= " WHERE grupo = '$grupo'";
}

// Ejecutar la consulta SQL
$resultado = $conn->query($sql);

// Construir la tabla HTML con los resultados de la consulta
$table = "<table id='tabla-articulos'>";
$table .= "<tr><th>CÓDIGO</th><th>ARTÍCULO</th><th>GRUPO</th><th>PESO</th><th>VALOR REPO</th><th>CÓD. ALTERNO</th><th>TIPO</th></tr>";
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $table .= "<tr>";
        $table .= "<td>" . $fila["codigo"] . "</td>";
        $table .= "<td>" . $fila["articulo"] . "</td>";
        $table .= "<td>" . $fila["grupo"] . "</td>";
        $table .= "<td>" . $fila["peso"] . "</td>";
        $table .= "<td>" . $fila["valorRepo"] . "</td>";
        $table .= "<td>" . $fila["codAlterno"] . "</td>";
        $table .= "<td>" . $fila["tipo"] . "</td>";
        $table .= "</tr>";
    }
}
$table .= "</table>";

echo $table;

$conn->close();
?>
