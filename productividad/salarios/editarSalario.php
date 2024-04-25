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
?>

<?php
// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se recibieron todos los datos necesarios del formulario
    if (isset($_POST['id']) && isset($_POST['año']) && isset($_POST['horas_mensuales']) && isset($_POST['horas_semanales']) && isset($_POST['descripcion']) && isset($_POST['salario_minimo']) && isset($_POST['auxilio_tte']) && isset($_POST['cesantias']) && isset($_POST['int_cesantias']) && isset($_POST['prima']) && isset($_POST['vacaciones']) && isset($_POST['salud_empleado']) && isset($_POST['salud_empleador']) && isset($_POST['pension_empleado']) && isset($_POST['pension_empleador']) && isset($_POST['caja_compensacion']) && isset($_POST['arl']) && isset($_POST['dotacion']) && isset($_POST['total_porcentaje']) && isset($_POST['costo_mensual']) && isset($_POST['costo_hora'])) {
        
        // Recibir los datos del formulario
        $id = $_POST['id'];
        $año = $_POST['año'];
        $horas_mensuales = $_POST['horas_mensuales'];
        $horas_semanales = $_POST['horas_semanales'];
        $descripcion = $_POST['descripcion'];
        $salario_minimo = $_POST['salario_minimo'];
        $auxilio_tte = $_POST['auxilio_tte'];
        $cesantias = $_POST['cesantias'];
        $int_cesantias = $_POST['int_cesantias'];
        $prima = $_POST['prima'];
        $vacaciones = $_POST['vacaciones'];
        $salud_empleado = $_POST['salud_empleado'];
        $salud_empleador = $_POST['salud_empleador'];
        $pension_empleado = $_POST['pension_empleado'];
        $pension_empleador = $_POST['pension_empleador'];
        $caja_compensacion = $_POST['caja_compensacion'];
        $arl = $_POST['arl'];
        $dotacion = $_POST['dotacion'];
        $total_porcentaje = $_POST['total_porcentaje'];
        $costo_mensual = $_POST['costo_mensual'];
        $costo_hora = $_POST['costo_hora'];

        // Conexión a la base de datos (ajusta los valores según tu configuración)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "datosgenerales";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("CONEXIÓN FALLIDA: " . $conn->connect_error);
        }

        // Preparar la consulta SQL para actualizar el salario con los datos proporcionados
        $sql = "UPDATE salarios SET año='$año', horas_mensuales='$horas_mensuales', horas_semanales='$horas_semanales', descripcion='$descripcion', salario_minimo='$salario_minimo', auxilio_tte='$auxilio_tte', cesantias='$cesantias', int_cesantias='$int_cesantias', prima='$prima', vacaciones='$vacaciones', salud_empleado='$salud_empleado', salud_empleador='$salud_empleador', pension_empleado='$pension_empleado', pension_empleador='$pension_empleador', caja_compensacion='$caja_compensacion', arl='$arl', dotacion='$dotacion', total_porcentaje='$total_porcentaje', costo_mensual='$costo_mensual', costo_hora='$costo_hora' WHERE id='$id'";

        // Ejecutar la consulta SQL
        if ($conn->query($sql) === TRUE) {
            // Si la actualización es exitosa, responder con un código 200 (OK)
            http_response_code(200);
            echo json_encode(array("message" => "SALARIO ACTUALIZADO CORRECTAMENTE."));
        } else {
            // Si hay un error al actualizar, responder con un código 500 (Error interno del servidor)
            http_response_code(500);
            echo json_encode(array("message" => "ERROR AL ACTUALIZAR EL SALARIO: " . $conn->error));
        }

        // Cerrar la conexión a la base de datos
        $conn->close();
    } else {
        // Si no se recibieron todos los datos necesarios del formulario, responder con un código 400 (Solicitud incorrecta)
        http_response_code(400);
        echo json_encode(array("message" => "DATOS INCOMPLETOS PARA ACTUALIZAR EL SALARIO."));
    }
} else {
    // Si la solicitud no fue de tipo POST, responder con un código 405 (Método no permitido)
    http_response_code(405);
    echo json_encode(array("message" => "MÉTODO NO PERMITIDO."));
}
?>
