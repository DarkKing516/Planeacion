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

// Función para mostrar un mensaje de alerta
function mostrarAlerta($mensaje) {
    if ($mensaje === 'guardado') {
        echo "<script>alert('EMPLEADO GUARDADO CORRECTAMENTE');</script>";
    } elseif ($mensaje === 'error') {
        echo "<script>alert('ERROR AL GUARDAR EL EMPLEADO');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CREAR EMPLEADO.</title>
    <!-- Estilos CSS -->
    <link rel="stylesheet" type="text/css" href="crearEmpleado.css">
   
</head>
<body>

<a href="/productividad/menu.php">IR AL MENÚ</a>
<a href="tablaEmpleados.php">IR A LA TABLA DE EMPLEADOS</a>

<h1>CREAR EMPLEADO.</h1>

<?php mostrarAlerta(isset($_GET['mensaje']) ? $_GET['mensaje'] : ''); ?>

<form action="guardarEmpleado.php" method="post" id="crearEmpleadoForm">

<p>POR FAVOR, INGRESE LOS VALORES SIN EL SIGNO PESOS, SIN COMAS NI PUNTOS, PARA NO DAÑAR EL ORDEN DE LA LISTA QUE YA SE IMPORTÓ.</p>

    <label for="estado" class="front-label">ESTADO:</label>
    <select id="estado" name="estado" required>
        <option value="" disabled selected>SELECCIONE EL ESTADO</option>
        <option value="ACTIVO">ACTIVO</option>
        <option value="INACTIVO">INACTIVO</option>
    </select>

    <label for="cedula" class="front-label">CÉDULA:</label>
    <input type="text" id="cedula" name="cedula" required>
    <div id="cedulaError" style="color: red;"></div>

    <label for="nombre" class="front-label">NOMBRE:</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="cargo" class="front-label">CARGO:</label>
    <input type="text" id="cargo" name="cargo" required>

    <label for="sede" class="front-label">SEDE:</label>
    <select id="sede" name="sede" required>
        <option value="" disabled selected hidden>SELECCIONE UNA SEDE</option>
        <?php include_once 'obtenerSedes.php'; // Incluir el archivo obtenerSedes.php ?>
    </select>

    <label for="fecha" class="front-label">FECHA:</label>
    <input type="date" id="fecha" name="fecha" placeholder="DD/MM/AAAA" required>

    <label for="basico" class="front-label">BÁSICO:</label>
    <input type="text" id="basico" name="basico" required oninput="calcularValorHora()">

    <label for="vHora" class="front-label">VALOR HORA:</label>
    <input type="text" id="vHora" name="vHora" required readonly>

    <button type="submit">GUARDAR</button>
</form>

<script>
document.getElementById('cedula').addEventListener('blur', function() {
    var cedula = this.value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var respuesta = this.responseText;
            if (respuesta.trim() !== "") {
                eval(respuesta);
            }
        }
    };
    xhttp.open("GET", "verificarCedula.php?cedula=" + cedula, true);
    xhttp.send();
});

function calcularValorHora() {
    var basico = parseFloat(document.getElementById("basico").value.replace(",", ""));
    var vHora = basico / 240;
    document.getElementById("vHora").value = vHora.toFixed(0);
}
</script>

</body>
</html>
