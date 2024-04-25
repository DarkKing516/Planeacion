<?php
// Conexión a la base de datos (reemplaza los valores con los de tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datosgenerales";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("CONEXIÓN FALLIDA: " . $conn->connect_error);
}

// Obtener los datos del formulario
$sede = $_POST['sede'];
$usuario = $_POST['usuario'];
$fecha = $_POST['fecha'];
$numero = $_POST ['numero'];
$cedula = $_POST['cedula'];
$nombre = $_POST['nombre'];
$grupo = $_POST['grupo'];
$cod_articulo = $_POST['cod_articulo'];
$articulo = $_POST['articulo'];
$nombre_estandar = $_POST['nombre_estandar'];
$peso_articulo = $_POST['peso_articulo'];
$horas_dia = $_POST['horas_dia'];
$minutos_dia = $_POST['minutos_dia'];
$tarea_dia = $_POST['tarea_dia'];
$peso_total = $_POST['peso_total'];
$observacion = $_POST['observacion']; // Asegúrate de que este campo tenga el atributo name="observacion" en tu formulario HTML

// Preparar la consulta SQL para insertar los datos en la tabla 'planeacion'
$sql = "INSERT INTO planeacion (sede, usuario, fecha, numero, cedula, nombre, grupo, cod_articulo, articulo, nombre_estandar, peso_articulo, horas_dia, minutos_dia, tarea_dia, peso_total, observacion) 
        VALUES ('$sede', '$usuario', '$fecha', '$numero', '$cedula', '$nombre', '$grupo', '$cod_articulo', '$articulo', '$nombre_estandar', '$peso_articulo', '$horas_dia', '$minutos_dia', '$tarea_dia', '$peso_total', '$observacion')";

// Ejecutar la consulta SQL
if ($conn->query($sql) === TRUE) {
    echo "DATOS GUARDADOS CORRECTAMENTE";
} else {
    echo "ERROR AL GUARDAR LOS DATOS: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
