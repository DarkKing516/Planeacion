<?php
// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $sede = $_POST['sede'];
    $usuario = $_POST['usuario'];
    $fecha = $_POST['fecha'];
    $cedula[1] = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $grupo = $_POST['grupo'];
    $cod_articulo = $_POST['cod_articulo'];
    $articulo = $_POST['articulo'];
    $nombre_estandar = $_POST['nombre_estandar'];
    $t_estandar = $_POST['t_estandar'];
    $peso_articulo = $_POST['peso_articulo'];
    $horas_dia = $_POST['horas_dia'];
    $minutos_dia = $_POST['minutos_dia'];
    $tarea_dia = $_POST['tarea_dia'];
    $peso_total = $_POST['peso_total'];
    $observacion = $_POST['observacion'];
    // Otros campos del formulario...

    // Aquí puedes validar y procesar los datos según tus requerimientos antes de enviarlos a guardar_datos.php
    
    // Redirigir a guardar_datos.php con los datos obtenidos
    header("Location: guardar_datos.php?sede=$sede&usuario=$usuario&fecha=$fecha&cedula=$cedula&nombre=$nombre&grupo=$grupo&cod_articulo=$cod_articulo&articulo=$articulo&nombre_estandar=$nombre_estandar&t_estandar=$t_estandar&peso_articulo=$peso_articulo&horas_dia=$horas_dia&minutos_dia=$minutos_dia&tarea_dia=$tarea_dia&peso_total=$peso_total&observacion=$observacion");
    // Agrega otros campos en la URL si es necesario

    exit(); // Asegurar que el script se detenga después de redirigir
} else {
    // Si no se recibieron los datos del formulario, mostrar un mensaje de error
    echo "Error: No se recibieron los datos del formulario";
}
?>
