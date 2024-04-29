<?php
// Establecer la conexión a la base de datos MySQL
$server = "localhost";
$user = "root";
$pass = "";
$db = "datosgenerales";

// Crear la conexión
$conn = new mysqli($server, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    // Si hay un error de conexión, devolver un mensaje de error
    echo "ERROR DE CONEXIÓN A LA BASE DE DATOS";
    exit();
}

// Consultar el último código almacenado en la tabla descansos
$sql = "SELECT MAX(cod_descanso) AS max_codigo FROM descansos";
$result = $conn->query($sql);

// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Obtener el último código y devolverlo como respuesta
    echo $row["max_codigo"];
} else {
    // Si no se encontraron resultados, devolver 0 como código
    echo "0";
}

// Cerrar la conexión
$conn->close();
?>
