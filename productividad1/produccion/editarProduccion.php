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

// Consulta SQL para obtener los nombres de la tabla 'articulos'
$sql_articulos = "SELECT DISTINCT articulo FROM articulos";
$result_articulos = $conn->query($sql_articulos);

// Verificar si hay resultados y construir las opciones del select
$options_articulos = '';
if ($result_articulos->num_rows > 0) {
    while($row = $result_articulos->fetch_assoc()) {
        $options_articulos .= "<option value='{$row['articulo']}'>{$row['articulo']}</option>";
    }
} else {
    $options_articulos = "<option value='' disabled>NO HAY ARTÍCULOS DISPONIBLES</option>";
}

// Verificar si se ha enviado el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar valores del formulario
    $id = $_POST['id'];
    $fecha = $_POST['fecha'];
    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $sede = $_POST['sede'];
    $grupo = $_POST['grupo'];
    $articulo = $_POST['articulo'];
    $proceso = $_POST['proceso'];
    $hRegistradas = $_POST['hRegistradas'];
    $hRegistrar = $_POST['hRegistrar'];
    $horasExtra = $_POST['horasExt'];
    $descansoUno = isset($_POST['descansoUno']) ? 'SI' : 'NO';
    $descansoDos = isset($_POST['descansoDos']) ? 'SI' : 'NO';
    $almuerzo = isset($_POST['almuerzo']) ? 'SI' : 'NO';
    $cDisponible = $_POST['cDisponible'];
    $cTaller = $_POST['cTaller'];
    $tEquipo = $_POST['tEquipo'];

    // Preparar la consulta de actualización
    $stmt = $conn->prepare("UPDATE produccion SET 
        fecha=?, nombre=?, cedula=?, sede=?, grupo=?, 
        articulo=?, proceso=?, hRegistradas=?, 
        hRegistrar=?, horasExtra=?, descansoUno=?, descansoDos=?, almuerzo=?, 
        cDisponible=?, cTaller=?, tEquipo=? WHERE id=?");

    // Vincular parámetros
    $stmt->bind_param("sssssssssssssssssssi", $fecha, $nombre, $cedula, $sede, $grupo, 
        $articulo, $proceso, $hRegistradas, $hRegistrar, $horasExtra, 
        $descansoUno, $descansoDos, $almuerzo, $cDisponible, $cTaller, $tEquipo, $id);

    // Ejecutar la consulta
    $stmt->execute();

    // Verificar si la actualización fue exitosa
    if ($stmt->affected_rows > 0) {
        // Redireccionar a la tabla de producción
        header("Location: tablaProduccion.php");
        exit(); // Asegúrate de salir después de la redirección
    } else {
        echo "ERROR AL ACTUALIZAR: " . $stmt->error;
    }

    // Cerrar la consulta preparada
    $stmt->close();
}

// Obtener el ID del registro a editar
$id = $_GET['id'];

// Consultar el registro actual en la base de datos
$consulta = "SELECT * FROM produccion WHERE id = $id";
$resultado = $conn->query($consulta);

// Obtener los datos del registro actual
if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $fecha = $fila['fecha'];
    $nombre = $fila['nombre'];
    $cedula = $fila['cedula'];
    $sede = $fila['sede'];
    $grupo = $fila['grupo'];
    $articulo = $fila['articulo'];
    $proceso = $fila['proceso'];
    $hRegistradas = $fila['hRegistradas'];
    $hRegistrar = $fila['hRegistrar'];
    $horasExtra = $fila['horasExtra'];
    $descansoUno = $fila['descansoUno'];
    $descansoDos = $fila['descansoDos'];
    $almuerzo = $fila['almuerzo'];
    $cDisponible = $fila['cDisponible'];
    $cTaller = $fila['cTaller'];
    $tEquipo = $fila['tEquipo'];
} else {
    echo "REGISTRO NO ENCONTRADO.";
}

// Consultar la lista de nombres de empleados
$consultaEmpleados = "SELECT nombre FROM empleados";
$resultadoEmpleados = $conn->query($consultaEmpleados);

// Función para obtener datos de empleados
function obtenerDatosDeEmpleados() {
    // Crear una conexión a la base de datos (ajusta los parámetros según tu configuración)
    $conexion = new mysqli("localhost", "root", "", "datosgenerales");

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("ERROR DE CONEXIÓN A LA BASE DE DATOS: " . $conexion->connect_error);
    }

    // Consulta SQL para obtener los datos de empleados
    $sql = "SELECT nombre, cedula FROM empleados";

    // Ejecutar la consulta
    $resultado = $conexion->query($sql);

    // Verificar si la consulta se ejecutó correctamente
    if ($resultado === false) {
        die("ERROR AL EJECUTAR LA CONSULTA: " . $conexion->error);
    }

    // Verificar si se obtuvieron resultados
    if ($resultado->num_rows > 0) {
        // Inicializar el array de datos de empleados
        $datosEmpleados = array();

        // Iterar sobre los resultados y almacenarlos en el array
        while ($fila = $resultado->fetch_assoc()) {
            $nombre = $fila["nombre"];
            $cedula = $fila["cedula"];
            $datosEmpleados[$nombre] = $cedula;
        }

        // Cerrar la conexión a la base de datos
        $conexion->close();

        // Retornar el array de datos de empleados
        return $datosEmpleados;
    } else {
        // Si no se obtuvieron resultados, mostrar un mensaje de error
        echo "NO SE ENCONTRARON EMPLEADOS EN LA BASE DE DATOS.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDITAR PRODUCCIÓN.</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Agregado jQuery -->
    <!-- Estilos CSS -->
    <style>
        /* Estilos para la página */
        body {
            background-color: #FFFFFF;
            color: #000000;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        h1, h2 {
            text-align: center;
            color: #244183; 
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 10px solid #244183;
            border-radius: 50px;
            position: relative;
            background: linear-gradient(to right, #ffe600, #ffe600 75%, transparent 50%);
            overflow: hidden;
        }

        form::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 50%;
            border-left: 20px solid #244183;
            transform: translateX(-50%);
            z-index: -1;
            background-color: transparent;
            border-left: none;
            width: 75%;
        }

        label.front-label,
        select.front-select {
            text-align: center; 
            color: #244183; 
            font-weight: bold; 
        }

        input,
        select {
            font-weight: bold;
            color: #244183;
            display: block;
            margin-bottom: 10px;
            text-align: center;
            position: relative;
            z-index: 2;
            width: 95%; 
            padding: 10px;
            margin-top: 5px;
            border: 2px solid #244183;
            border-radius: 15px; 
        }

        input::placeholder {
            color: #244183;
        }

        input[type="checkbox"] {
            width: 15px; 
            height: 15px;
        }

        input[type="submit"] {
            cursor: pointer;
            background-color: #244183;
            color: #FFFFFF;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0F3057;
        }

        /* Estilos para checkboxes */
        .checkbox-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .checkbox-label {
            margin-right: 10px;
            font-weight: bold;
            color: #244183;
        }

        /* Estilo base para el checkbox */
        .checkbox-container input {
            opacity: 0;
            position: absolute;
        }

        /* Estilo del contenedor del checkbox */
        .checkmark {
            position: relative;
            display: inline-block;
            width: 20px;
            height: 20px;
            background-color: #fff;
            border: 2px solid #244183;
            border-radius: 3px;
        }

        /* Estilo del chulo cuando el checkbox está marcado */
        .checkbox-container input:checked + .checkmark:after {
            content: "\2713"; /* Código del chulo (checkmark) Unicode */
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 16px;
            height: 16px;
            font-size: 15px; /* Tamaño del chulo */
            font-weight: bold; /* Hacer el chulo más grueso */
            color: #FFFFFF; /* Color del chulo cuando está marcado */
        }

        /* Estilo del contenedor del checkbox cuando está marcado */
        .checkbox-container input:checked + .checkmark {
            background-color: #4CAF50; /* Color verde de fondo cuando está marcado */
            border-color: #244183; /* Color azul del borde cuando está marcado */
            color: #244183; /* Color del chulo cuando está marcado */
            display: flex; /* Utiliza flexbox para centrar verticalmente */
            align-items: center; /* Centra verticalmente el contenido */
            justify-content: center; /* Centra horizontalmente el contenido */
        }

        button {
            background-color: #244183;
            color: #FFFFFF; 
            padding: 10px 20px;
            border: 2px solid #244183;
            position: relative;
            left: 45%;
            margin-top: 10px;
            border-radius: 30px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s, color 0.3s, border-color 0.3s;
            display: block;
        }

        button:hover {
            background-color: #ffe600; 
            color: #244183;
            border-color: #244183;
        }

        a {
            text-decoration: none;
        }

/* Estilo para el select con ID 'articulo' */
#articulo {
    width: 98.5%; /* Ajusta el ancho según sea necesario */
    padding: 10px; /* Añade relleno interno */
    border: 2px solid #244183; /* Añade un borde de 2px con color #244183 */
    border-radius: 15px; /* Añade esquinas redondeadas */
    font-weight: bold; /* Establece la negrita para el texto */
    color: #244183; /* Cambia el color del texto */
    background-color: #FFFFFF; /* Cambia el color de fondo */
}

/* Estilos para el select de Select2 */
.select2-container--default .select2-selection--single {
    border: 2px solid #244183; /* Borde de 2px y color azul */
    border-radius: 15px; /* Borde redondeado de 10px */
    height: 40px; /* Altura personalizada */
    line-height: 1.5; /* Alineación vertical del texto */
}

/* Estilo para el campo de búsqueda dentro de las opciones del Select2 */
.select2-container--default .select2-dropdown .select2-search__field {
    border: 2px solid #244183; /* Borde de 2px y color azul */
    border-radius: 5px; /* Borde redondeado levemente */
    box-sizing: border-box; /* Incluir el borde en el tamaño total */
    height: 40px; /* Altura personalizada */
    line-height: 1.5; /* Alineación vertical del texto */
    padding: 5px 10px; /* Espaciado interno */
    width: 98.5%; /* Ancho completo */
}

/* Estilo para las opciones del Select2 al pasar el puntero */
.select2-container--default .select2-results__option--highlighted {
    background-color: #ffe600 !important; /* Color amarillo */
    color: #000000 !important; /* Color de texto negro */
}

   /* Estilos para el texto dentro de Select2 */
   .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #244183; /* Cambia el color del texto a azul */
        text-align: center; /* Centra el texto horizontalmente */
    }

    /* Estilos para las opciones dentro de Select2 */
    .select2-container--default .select2-results__option {
        text-align: center; /* Centra las opciones horizontalmente */
    }
    
    #botones-container {
            position: absolute;
            top: 0px; /* Cambia el margen superior según necesites */
            left: -200px; /* Cambia el margen izquierdo según necesites */
        }

        /* Estilos para los botones */
        #botones-container button {
            display: inline-block; /* Para que los botones estén uno al lado del otro */
            margin-right: 10px; /* Margen derecho entre los botones */
            background-color: #244183;
            color: #FFFFFF;
            padding: 10px 20px;
            border: 2px solid #244183;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        #botones-container button:hover {
            background-color: #0F3057;
        }

           /* Estilos para los textos de advertencia */
    .no-aplica {
        color: #FF0000; /* Color rojo */
        font-weight: bold; /* Hacer el texto en negrita */
        text-align: center;
    }
    </style>
</head>
<body>

    <!-- Enlace al inicio de la página -->
    <a href="/productividad/menu.php">
        <button>
            IR AL MENÚ
        </button>
    </a>

    <a href="tablaProduccion.php"><button type="button">VOLVER A TABLA DE PRODUCCION</button></a>
    <h1>EDITAR PRODUCCIÓN.</h1>

    <form method="post">

        <div class="white-box"></div>

        <label for="fecha" class="front-label">FECHA:</label>
        <input type="date" id="fecha" name="fecha" value="<?php echo $fecha; ?>" required>

        <label for="sedeSelect" class="front-label">SEDE:</label>
<select id="sedeSelect" name="sede" style="width: 98.5%;" onchange="filtrarNombresPorSede(this.value)">
    <option value="" disabled selected hidden>SELECCIONE UNA SEDE</option>
    <?php echo $options_sede; ?>
</select>

<label for="nombreSelect" class="front-label">NOMBRE DEL EMPLEADO:</label>
<select id="nombreSelect" name="nombre" onchange="actualizarCedula(this.value)" style="width: 98.5%;" class="select2">
            <!-- Las opciones se cargarán dinámicamente mediante AJAX -->
        </select>

        <label for="cedula" class="front-label">CEDULA:</label>
        <input type="text" id="cedula" name="cedula" value="<?php echo $cedula; ?>" required>

        <label for="grupo" class="front-label">GRUPO:</label>
        <input type="text" id="grupo" name="grupo" value="<?php echo $grupo; ?>" required>

        <label for="proceso" class="front-label">PROCESO:</label>
        <input type="text" id="proceso" name="proceso" value="<?php echo $proceso; ?>" required>

        <label for="articuloSelect" class="front-label">ARTÍCULO:</label>
    <select id="articuloSelect" name="articulo" style="width: 98.5%;">
        <option value="" disabled selected hidden>SELECCIONE UN ARTÍCULO</option>
        <?php echo $options_articulos; ?>
    </select>

         <label for="hRegistradas" class="front-label">HORAS REGISTRADAS:</label>
        <input type="decimal" id="hRegistradas" name="hRegistradas" oninput="calcularHorasExtra()" placeholder="EJEMPLO: 8.5">

        <label for="hRegistrar" class="front-label">HORAS POR REGISTRAR:</label>
        <input type="decimal" id="hRegistrar" name="hRegistrar" oninput="calcularHorasExtra()" placeholder="EJEMPLO: 8.5">

        <label for="horasExtra" class="front-label">HORAS EXTRA:</label>
        <input type="decimal" id="horasExtra" name="horasExtra" placeholder="" readonly>

        <div class="checkbox-container">
            <input type="checkbox" id="descansoUno" name="descansoUno" <?php if ($descansoUno == "SI") echo "checked"; ?>>
            <label class="checkbox-label" for="descansoUno">DESCANSO 1</label>
            <span class="checkmark"></span>
        </div>

        <div class="checkbox-container">
            <input type="checkbox" id="descansoDos" name="descansoDos" <?php if ($descansoDos == "SI") echo "checked"; ?>>
            <label class="checkbox-label" for="descansoDos">DESCANSO 2</label>
            <span class="checkmark"></span>
        </div>

        <div class="checkbox-container">
            <input type="checkbox" id="almuerzo" name="almuerzo" <?php if ($almuerzo == "SI") echo "checked"; ?>>
            <label class="checkbox-label" for="almuerzo">ALMUERZO</label>
            <span class="checkmark"></span>
        </div>

        <label for="cDisponible" class="front-label">CANTIDAD DISPONIBLE:</label>
<input type="number" id="cDisponible" name="cDisponible" placeholder="EJEMPLO: 10" value="<?php echo $cDisponible; ?>" oninput="calcularSuma()">

<label for="cTaller" class="front-label">CANTIDAD EN TALLER:</label>
<input type="number" id="cTaller" name="cTaller" placeholder="EJEMPLO: 10" value="<?php echo $cTaller; ?>" oninput="calcularSuma()">

<label for="tEquipo" class="front-label">TOTAL EQUIPO:</label>
<input type="text" id="tEquipo" name="tEquipo" placeholder="TOTAL EQUIPO" readonly>

        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <input type="submit" value="ACTUALIZAR REGISTRO">

    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

function actualizarCedula(nombreSeleccionado) {
            var cedulaInput = document.getElementById('cedula');
            $.ajax({
                url: 'obtenerCedula.php',
                method: 'POST',
                data: { nombre: nombreSeleccionado },
                success: function(response) {
                    cedulaInput.value = response;
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

$(document).ready(function() {
        // Inicializar Select2 en todos los select
        $('select').select2({
            dropdownParent: $('select').parent(),
            minimumResultsForSearch: 0
        });
    });



    function calcularHorasExtra() {
  // Obtener los valores de las horas registradas y por registrar
  var hRegistradas = parseFloat(document.getElementById("hRegistradas").value);
  var hRegistrar = parseFloat(document.getElementById("hRegistrar").value);

  // Calcular la suma de horas registradas y por registrar
  var horasExtra = hRegistradas + hRegistrar;

  // Verificar si el resultado supera 8.5
  if (horasExtra > 8.5) {
    // Calcular el excedente sobre 8.5
    var excedente = horasExtra - 8.5;
    // Actualizar el valor del placeholder de 'horasExtra' con el excedente
    document.getElementById("horasExtra").placeholder = excedente.toFixed(1);
  } else {
    // Si no supera 8.5, establecer el valor del placeholder en 0
    document.getElementById("horasExtra").placeholder = "0";
  }
  
  // Verificar si el resultado en el placeholder supera 6.5
  if (parseFloat(document.getElementById("horasExtra").placeholder) > 6.5) {
    // Mostrar alerta
    alert("LAS HORAS EXTRA MÁXIMAS SON 6.5, POR FAVOR CORRIJA LOS VALORES DE HORAS REGISTRADAS Y HORAS POR REGISTRAR Y QUE NO SUPEREN LAS 15 HORAS INCLUYENDO LAS HORAS EXTRA");
  }
}

 // Función para calcular la suma de cDisponible y cTaller y mostrar el resultado en tEquipo
 function calcularSuma() {
        // Obtener los valores de cDisponible y cTaller
        var cDisponibleValue = parseFloat(document.getElementById('cDisponible').value) || 0;
        var cTallerValue = parseFloat(document.getElementById('cTaller').value) || 0;
        
        // Calcular la suma
        var suma = cDisponibleValue + cTallerValue;
        
        // Actualizar el valor de tEquipo con la suma
        document.getElementById('tEquipo').value = suma.toFixed(0); // Redondear a 2 decimales
    }
</script>

</body>
</html>

<?php
// Cerrar la conexión a la base de datos después de utilizarla
$conn->close();
?>
