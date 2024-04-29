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
$conn = new mysqli($server, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("ERROR DE CONEXIÓN: " . $conn->connect_error);
}

// Proceso para eliminar un registro si se ha enviado el formulario
if (isset($_POST['eliminar'])) {
    $idEliminar = $_POST['eliminar'];
    $eliminarRegistro = "DELETE FROM empleados WHERE id = '$idEliminar'";
    $resultadoEliminar = $conn->query($eliminarRegistro);
}

// Consulta para seleccionar todos los registros de la tabla 'empleados'
$consulta = "SELECT * FROM empleados";
$resultado = $conn->query($consulta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TABLA EMPLEADOS.</title>
    <!-- Estilos CSS -->
    <link rel="stylesheet" type="text/css" href="tablaEmpleados.css">

</head>
<body>

    <!-- Botones de navegación y acciones -->
    <a href="/productividad/menu.php">
        <button>
            IR AL MENÚ
        </button>
    </a>
    <a href="empleados.php"><button type="button">VOLVER A REGISTRO DE EMPLEADOS</button></a>
    <a href="tablaEmpleados.php"><button type="button">REFRESCAR</button></a>

    <!-- Encabezado de la página -->
    <h1>REGISTROS ALMACENADOS EN LA TABLA EMPLEADOS.</h1>

    <!-- Tabla para mostrar los registros de la base de datos -->
    <table>
        <tr>
            <th>ESTADO</th>
            <th>CÉDULA</th>
            <th>NOMBRE</th>
            <th>CARGO</th>
            <th>SEDE</th>
            <th>FECHA INGRESO</th>
            <th>BÁSICO</th>
            <th>V / HORA</th>
            <th>EDITAR</th>
        </tr>

        <?php
        // Mostrar los registros en la tabla
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $fila["estado"] . "</td>";
                echo "<td>" . $fila["cedula"] . "</td>";
                echo "<td>" . $fila["nombre"] . "</td>";
                echo "<td>" . $fila["cargo"] . "</td>";
                echo "<td>" . $fila["sede"] . "</td>";
                echo "<td>" . $fila["fecha"] . "</td>";
                echo "<td>" . $fila["basico"] . "</td>";
                echo "<td>" . $fila["vHora"] . "</td>";
                echo "<td>";
                // Formulario para eliminar un registro
                echo "<form method='post' style='display:inline;'>";
                //echo "<button type='submit' name='eliminar' value='" . $fila["id"] . "'>Eliminar</button>";
                echo "</form>";
                // Enlace para editar un registro
                echo "<a href='editarEmpleado.php?id=" . $fila["id"] . "'><button type='button'>EDITAR</button></a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>NO HAY REGISTROS EN LA TABLA.</td></tr>";
        }
        ?>

    </table>

</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$conn->close();
?>
