<?php
// Verificar si se recibieron los datos del formulario por el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se recibió el ID del salario a actualizar
    if(isset($_GET['id'])) {
        $id = $_GET['id'];

        // Conexión a la base de datos (coloca tus credenciales correctas)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "datosgenerales";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Preparar y ejecutar la consulta SQL para actualizar el salario
        $stmt = $conn->prepare("UPDATE salarios SET año=?, horas_mensuales=?, horas_semanales=?, descripcion=?, salario_minimo=?, auxilio_tte=?, cesantias=?, int_cesantias=?, prima=?, vacaciones=?, salud_empleado=?, salud_empleador=?, pension_empleado=?, pension_empleador=?, caja_compensacion=?, arl=?, dotacion=?, total_porcentaje=?, costo_mensual=?, costo_hora=? WHERE id=?");

        $stmt->bind_param("ssssssssssssssssssssi", $_POST['año'], $_POST['horas_mensuales'], $_POST['horas_semanales'], $_POST['descripcion'], $_POST['salario_minimo'], $_POST['auxilio_tte'], $_POST['cesantias'], $_POST['int_cesantias'], $_POST['prima'], $_POST['vacaciones'], $_POST['salud_empleado'], $_POST['salud_empleador'], $_POST['pension_empleado'], $_POST['pension_empleador'], $_POST['caja_compensacion'], $_POST['arl'], $_POST['dotacion'], $_POST['total_porcentaje'], $_POST['costo_mensual'], $_POST['costo_hora'], $id);

        if ($stmt->execute()) {
            // Si la actualización fue exitosa, redirigir a la página de la tabla de salarios
            header("Location: tablaSalarios.php");
            exit();
        } else {
            // Si hubo un error durante la actualización, mostrar un mensaje de error
            echo "Error al actualizar el salario: " . $conn->error;
        }

        // Cerrar la conexión
        $stmt->close();
        $conn->close();
    } else {
        // Si no se recibió el ID del salario, mostrar un mensaje de error
        echo "ID del salario no proporcionado.";
    }
} else {
    // Si no se recibieron los datos por el método POST, mostrar un mensaje de error
    echo "Los datos del formulario no se recibieron correctamente.";
}
?>
