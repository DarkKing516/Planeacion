<?php
// Iniciar sesión
session_start();

// Verificar si no hay una sesión activa para el usuario
if (!isset($_SESSION['usuario'])) {
    // Mostrar mensaje de alerta
    echo "<script>alert('DEBES INICIAR SESIÓN PARA PODER INGRESAR A ESTA PÁGINA');</script>";
    
    // Redirigir al usuario de vuelta a la página de inicio de sesión
    header("Location: /productividad/index.php");
    exit(); // Asegurar que el script se detenga después de redirigir
}

// Conexión a la base de datos (reemplaza los valores con los de tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datosgenerales";

// Obtener datos del formulario
$cod_grupo = $_POST['cod_grupo'];
$grupo = $_POST['grupo']; // Cambiado: obtener el valor seleccionado del select
$cod_proceso = $_POST['cod_proceso'];
$proceso = $_POST['proceso']; // Cambiado: obtener el valor seleccionado del select
$cod_articulo = $_POST['cod_articulo'];
$articulo_codigo = $_POST['articulo']; // Obtener el código del artículo seleccionado
$peso = $_POST['peso'];
$nombre_estandar = $_POST['nombre_estandar'];
$t_estandar = $_POST['t_estandar'];
$cod_estandar = $_POST['codigo_estandar'];

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el nombre del grupo correspondiente al código
$sql_grupo = "SELECT nombre FROM grupos WHERE codigo = '$grupo'";
$result_grupo = $conn->query($sql_grupo);

if ($result_grupo->num_rows > 0) {
    $row_grupo = $result_grupo->fetch_assoc();
    $grupo_nombre = $row_grupo['nombre'];
    $grupo = $grupo_nombre; // Asignar el nombre del grupo a la variable $grupo
} else {
    // Si no se encuentra el grupo, asignar un valor por defecto o manejar el error según sea necesario
    $grupo_nombre = "Sin nombre";
    $grupo = $grupo_nombre; // Asignar el nombre por defecto del grupo a la variable $grupo
}

// Obtener el nombre del proceso correspondiente al código
$sql_proceso = "SELECT nombre FROM procesos WHERE codigo = '$proceso'";
$result_proceso = $conn->query($sql_proceso);

if ($result_proceso->num_rows > 0) {
    $row_proceso = $result_proceso->fetch_assoc();
    $proceso_nombre = $row_proceso['nombre'];
    $proceso = $proceso_nombre; // Asignar el nombre del proceso a la variable $proceso
} else {
    // Si no se encuentra el proceso, asignar un valor por defecto o manejar el error según sea necesario
    $proceso_nombre = "Sin nombre";
    $proceso = $proceso_nombre; // Asignar el nombre por defecto del proceso a la variable $proceso
}

// Obtener el nombre del artículo correspondiente al código
$sql_articulo = "SELECT articulo FROM articulos WHERE codigo = '$articulo_codigo'";
$result_articulo = $conn->query($sql_articulo);

if ($result_articulo->num_rows > 0) {
    $row_articulo = $result_articulo->fetch_assoc();
    $articulo = $row_articulo['articulo'];
} else {
    // Si no se encuentra el artículo, asignar un valor por defecto o manejar el error según sea necesario
    $articulo = "Sin nombre";
}

// Preparar la consulta SQL para insertar los datos en la tabla
$sql = "INSERT INTO estandar (cod_grupo, grupo, cod_proceso, proceso, cod_articulo, articulo, peso, nombre_estandar, cod_estandar, t_estandar)
VALUES ('$cod_grupo', '$grupo', '$cod_proceso', '$proceso', '$cod_articulo', '$articulo', '$peso', '$nombre_estandar', '$cod_estandar', '$t_estandar')";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
     echo "success";
} else {
    echo $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
