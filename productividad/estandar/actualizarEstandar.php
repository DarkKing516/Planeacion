<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datosgenerales";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del formulario
$cod_estandar = $_POST['cod_estandar'];
$nombre_estandar = $_POST['nombre_estandar'];
$t_estandar = $_POST['t_estandar'];
$cod_grupo = $_POST['cod_grupo'];
$cod_proceso = $_POST['proceso']; // Cambiar aquí si el nombre del campo en el formulario es diferente
$cod_articulo = $_POST['articulo']; // Cambiar aquí si el nombre del campo en el formulario es diferente

// Consulta SQL para actualizar los datos del registro
$sql = "UPDATE estandar SET nombre_estandar = '$nombre_estandar', t_estandar = '$t_estandar', cod_grupo = '$cod_grupo', cod_proceso = '$cod_proceso', cod_articulo = '$cod_articulo' WHERE cod_estandar = '$cod_estandar'";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('REGISTRO ACTUALIZADO CORRECTAMENTE.'); window.location.href = 'editarEstandar.php';</script>";
} else {
    echo "<script>alert('ERROR AL ACTUALIZAR EL REGISTRO: " . $conn->error . "'); window.location.href = 'editarEstandar.php';</script>";
}

// Cerrar la conexión
$conn->close();
?>
