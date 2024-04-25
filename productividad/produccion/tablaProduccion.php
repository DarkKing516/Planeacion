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

// Obtener todos los registros de la tabla produccion
$sql = "SELECT * FROM produccion";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TABLA DE PRODUCCIÓN.</title>
    <link rel="stylesheet" href="tablaProduccion.css">
</head>
<body>
    <!-- Enlaces para navegar y refrescar -->
    <a href="/productividad/menu.php">
        <button>
            IR AL MENÚ
        </button>
    </a>
    <a href="produccion.php"><button type="button">VOLVER A REGISTRO DE PRODUCCIÓN</button></a>
    <a href="tablaProduccion.php"><button type="button">REFRESCAR</button></a>

    <h1>REGISTROS ALMACENADOS EN LA TABLA DE PRODUCCIÓN.</h1>
    <table>
        <tr>
            <th>SEDE</th>
            <th>USUARIO</th>
            <th>FECHA</th>
            <th>CÉDULA</th>
            <th>EMPLEADO</th>
            <th>CÓD. GRUPO</th>
            <th>GRUPO</th>
            <th>CÓD. ARTÍCULO</th>
            <th>ARTÍCULO</th>
            <th>ESTÁNDAR</th>
            <th>T. ESTÁNDAR</th>
            <th>INICIO</th>
            <th>FIN</th>
            <th>T. LABORADO</th>
            <th>DESCANSO 1</th>
            <th>DESCANSO 2</th>
            <th>ALMUERZO</th>
            <th>T. REAL</th>
            <th>HORAS EXTRA</th>
            <th>EQ. DISPONIBLE</th>
            <th>EQ. EN TALLER</th>
            <th>OBSERVACIONES</th>
            <th>EDITAR</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            // Mostrar datos de cada fila
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["sede"] . "</td>";
                echo "<td>" . $row["usuario"] . "</td>";
                echo "<td>" . $row["fecha"] . "</td>";
                echo "<td>" . $row["cedula"] . "</td>";
                echo "<td>" . $row["empleado"] . "</td>";
                echo "<td>" . $row["cod_grupo"] . "</td>";
                echo "<td>" . $row["grupo"] . "</td>";
                echo "<td>" . $row["cod_articulo"] . "</td>";
                echo "<td>" . $row["articulo"] . "</td>";
                echo "<td>" . $row["nombre_estandar"] . "</td>";
                echo "<td>" . $row["t_estandar"] . "</td>";
                echo "<td>" . $row["hora_inicio"] . "</td>";
                echo "<td>" . $row["hora_fin"] . "</td>";
                echo "<td>" . $row["tiempoLaborado"] . "</td>";
                echo "<td>" . $row["descanso1"] . "</td>";
                echo "<td>" . $row["descanso2"] . "</td>";
                echo "<td>" . $row["almuerzo"] . "</td>";
                echo "<td>" . $row["tiempoReal"] . "</td>";
                echo "<td>" . $row["horasExtra"] . "</td>";
                echo "<td>" . $row["eq_disponible"] . "</td>";
                echo "<td>" . $row["eq_taller"] . "</td>";
                echo "<td>" . $row["observaciones"] . "</td>";
                // Agregar más celdas aquí según las necesidades
                echo "<td><a href='editarProduccion.php?id=" . $row["id"] . "' class='button edit-button'>EDITAR</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>NO HAY REGISTROS EN LA TABLA.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
