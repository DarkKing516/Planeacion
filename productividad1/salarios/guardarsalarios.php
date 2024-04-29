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
// Recibir datos en formato JSON enviado desde JavaScript
$data = json_decode(file_get_contents("php://input"), true);

// Conexión a la base de datos (reemplaza los valores con los de tu configuración)
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

// Obtener datos del formulario
$año = $data['año'];
$horas_mensuales = $data['horas_mensuales'];
$horas_semanales = $data['horas_semanales'];
$descripcion = $data['descripcion'];
$salario_minimo = $data['salario_minimo'];
$auxilio_tte = $data['auxilio_tte'];
$cesantias = $data['cesantias'];
$int_cesantias = $data['int_cesantias'];
$prima = $data['prima'];
$vacaciones = $data['vacaciones'];
$salud_empleado = $data['salud_empleado'];
$salud_empleador = $data['salud_empleador'];
$pension_empleado = $data['pension_empleado'];
$pension_empleador = $data['pension_empleador'];
$caja_compensacion = $data['caja_compensacion'];
$arl = $data['arl'];
$dotacion = $data['dotacion'];
$total_porcentaje = $data['total_porcentaje'];
$costo_mensual = $data['costo_mensual'];
$costo_hora = $data['costo_hora'];

// Preparar la consulta SQL para insertar los datos en la tabla
$sql = "INSERT INTO salarios (año, horas_mensuales, horas_semanales, descripcion, salario_minimo, auxilio_tte, cesantias, int_cesantias, prima, vacaciones, salud_empleado, salud_empleador, pension_empleado, pension_empleador, caja_compensacion, arl, dotacion, total_porcentaje, costo_mensual, costo_hora) VALUES ('$año', '$horas_mensuales', '$horas_semanales', '$descripcion', '$salario_minimo', '$auxilio_tte', '$cesantias', '$int_cesantias', '$prima', '$vacaciones', '$salud_empleado', '$salud_empleador', '$pension_empleado', '$pension_empleador', '$caja_compensacion', '$arl', '$dotacion', '$total_porcentaje', '$costo_mensual', '$costo_hora')";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    // Si la inserción fue exitosa, devolvemos un objeto JSON con un mensaje de éxito
    $response = array("success" => true, "message" => "DATOS GUARDADOS CORRECTAMENTE");
} else {
    // Si hubo un error, devolvemos un objeto JSON con un mensaje de error
    $response = array("success" => false, "message" => "ERROR AL GUARDAR LOS DATOS: " . $conn->error);
}

// Cerrar la conexión
$conn->close();

// Devolver la respuesta como un objeto JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
