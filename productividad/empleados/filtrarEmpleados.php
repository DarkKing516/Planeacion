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

// Obtener los valores de estado, cargo y sede enviados por la solicitud AJAX
$estado = $_GET['estado'] ?? '';
$cargo = $_GET['cargo'] ?? '';
$sede = $_GET['sede'] ?? '';

// Construir la consulta SQL basada en los valores de estado, cargo y sede
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

// Ejecutar la consulta SQL
$resultado = $conn->query($sql);

// Construir la tabla HTML con los resultados de la consulta
$table = "<table id='tabla-empleados'>";
$table .= "<tr><th>ESTADO</th><th>CÉDULA</th><th>NOMBRE</th><th>CARGO</th><th>SEDE</th><th>FECHA</th><th>BÁSICO</th><th>V / HORA</th></tr>";
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $table .= "<tr>";
        $table .= "<td>" . $fila["estado"] . "</td>";
        $table .= "<td>" . $fila["cedula"] . "</td>";
        $table .= "<td>" . $fila["nombre"] . "</td>";
        $table .= "<td>" . $fila["cargo"] . "</td>";
        $table .= "<td>" . $fila["sede"] . "</td>";
        $table .= "<td>" . $fila["fecha"] . "</td>";
        $table .= "<td>" . $fila["basico"] . "</td>";
        $table .= "<td>" . $fila["vHora"] . "</td>";
        $table .= "</tr>";
    }
}
$table .= "</table>";

echo $table;

// Cerrar la conexión a la base de datos
$conn->close();
?>
