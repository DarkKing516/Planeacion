<?php
// Verificar si se recibió la sede seleccionada
if(isset($_POST['sede'])) {
    // Conexión a la base de datos (reemplaza los valores con los de tu configuración)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "datosgenerales";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    
    // Obtener la sede seleccionada
    $sede = $_POST['sede'];

    // Consulta SQL para obtener el personal autorizado de la sede seleccionada
    $sql_per_autorizado = "SELECT per_autorizado FROM sedes WHERE nombre_sede = '$sede'";
    $result_per_autorizado = $conn->query($sql_per_autorizado);

    // Consulta SQL para obtener los empleados de la sede seleccionada
    $sql_empleados = "SELECT DISTINCT cedula, nombre, grupo, cod_articulo, articulo, nombre_estandar, t_estandar, peso_articulo FROM empleados WHERE sede = '$sede'";
    $result_empleados = $conn->query($sql_empleados);

    // Verificar si se obtuvieron los datos correctamente
    if ($result_per_autorizado && $result_empleados) {
        // Obtener el personal autorizado
        $row_per_autorizado = $result_per_autorizado->fetch_assoc();
        $per_autorizado = $row_per_autorizado['per_autorizado'];

        // Almacenar el personal autorizado y los empleados en un array
        $data = array(
            'per_autorizado' => $per_autorizado,
            'empleados' => array()
        );

        // Obtener los datos de cada empleado
        while($row_empleado = $result_empleados->fetch_assoc()) {
            $data['empleados'][] = $row_empleado;
        }

        // Devolver los datos en formato JSON
        echo json_encode($data);
    } else {
        // Devolver un mensaje de error si no se encontraron datos
        echo json_encode(array('error' => 'No se encontraron datos para la sede seleccionada'));
    }

    // Cerrar la conexión
    $conn->close();
} else {
    // Si no se proporcionó la sede seleccionada, mostrar un mensaje de error
    echo json_encode(array('error' => 'No se proporcionó la sede seleccionada'));
}
?>
