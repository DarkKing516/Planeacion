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
// Establecer la conexión a la base de datos MySQL
$server = "localhost";
$user = "root";
$pass = "";
$db = "datosgenerales";

// Crear la conexión
$conn = new mysqli($server, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("ERROR DE CONEXIÓN: " . $conn->connect_error);
}

$sql_sede = "SELECT DISTINCT sede FROM empleados";
$result_sede = $conn->query($sql_sede);

// Verificar si hay resultados y construir las opciones del select
$options_sede = '';
if ($result_sede->num_rows > 0) {
    while($row = $result_sede->fetch_assoc()) {
        $options_sede .= "<option value='{$row['sede']}'>{$row['sede']}</option>";
    }
} else {
    $options_sede = "<option value='' disabled>NO HAY SEDES DISPONIBLES</option>";
}

// Verificar si se ha enviado el ID de la sede
if(isset($_POST['sede_id'])) {
    $sede_id = $_POST['sede_id'];

    // Obtener los nombres de los empleados para la sede seleccionada
    $sql_nombres = "SELECT nombre FROM empleados WHERE sede = ?";
    $stmt = $conn->prepare($sql_nombres);
    $stmt->bind_param("s", $sede_id);
    $stmt->execute();
    $result_nombres = $stmt->get_result();

    // Construir un array con los nombres de los empleados
    $nombres_empleados = array();
    while ($row = $result_nombres->fetch_assoc()) {
        $nombres_empleados[] = $row['nombre'];
    }

    // Devolver los nombres de los empleados en formato JSON
    echo json_encode($nombres_empleados);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEDE Y NOMBRE</title>
    <!-- Agrega los estilos de Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Estilo para agregar espacio entre los selectores -->
    <style>
        /* Estilo para el contenedor del select de 'sede' */
        .select-container {
            margin-bottom: 140px;
        }
    </style>
</head>
<body>
    <h1>SELECCIONE SEDE Y NOMBRE</h1>
    
    <!-- Contenedor para el Select de la Sede -->
    <div class="select-container">
    <label for="sedeSelect" class="front-label">SEDE:</label>
<select id="sedeSelect" name="sede" style="width: 98.5%;" onchange="filtrarNombresPorSede(this.value)">
    <option value="" disabled selected hidden>SELECCIONE UNA SEDE</option>
    <?php echo $options_sede; ?>
</select>
    </div>
    
    <!-- Select para los nombres de los empleados -->
    <div class="select-container">
        <label for="nombreSelect" class="front-label">NOMBRE:</label>
        <select id="nombreSelect" name="nombre" onchange="actualizarCedula(this.value)" style="width: 98.5%;" class="select2">
            <!-- Las opciones se cargarán dinámicamente mediante AJAX -->
        </select>
    </div>
    
    <!-- Incluye jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Incluye Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <!-- Script personalizado para cargar las opciones de sedeNombres.php -->
    <script>
        $(document).ready(function() {
            // Inicializa el select2 para el select de 'nombre'
            $('#nombreSelect').select2({
                dropdownParent: $('#nombreSelect').parent(),
                minimumResultsForSearch: 0 // Muestra siempre la barra de búsqueda
            });
            
            // Inicializa el select2 para el select de 'sede' pero sin la barra de búsqueda
            $('#sedeSelect').select2({
                dropdownParent: $('#sedeSelect').parent(),
                minimumResultsForSearch: Infinity // Desactiva la barra de búsqueda
            });
            
            // Cuando se seleccione una sede
            $('#sedeSelect').change(function() {
                var sedeId = $(this).val();
                
                // Llama a sedeNombres.php con la sede seleccionada
                $.ajax({
                    url: 'sedeNombres.php',
                    type: 'POST',
                    data: { sede_id: sedeId },
                    dataType: 'json',
                    success: function(response) {
                        // Borra las opciones actuales del select de nombre
                        $('#nombreSelect').empty();
                        
                        // Agrega las nuevas opciones
                        $.each(response, function(index, nombre) {
                            $('#nombreSelect').append('<option value="' + nombre + '">' + nombre + '</option>');
                        });
                        
                        // Actualiza el select2
                        $('#nombreSelect').select2({
                            dropdownParent: $('#nombreSelect').parent(),
                            minimumResultsForSearch: 0
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('ERROR AL OBTENER NOMBRES:', error);
                    }
                });
            });
        });
    </script>
</body>
</html>
