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
    $eliminarRegistro = "DELETE FROM articulos WHERE id = '$idEliminar'";
    $resultadoEliminar = $conn->query($eliminarRegistro);
}

// Consulta para seleccionar todos los registros de la tabla 'articulos'
$consulta = "SELECT * FROM articulos";
$resultado = $conn->query($consulta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TABLA DE ARTÍCULOS.</title>
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="tablaArticulos.css">
</head>
<body>

    <!-- Botones de navegación y acciones -->
    <a href="/productividad/menu.php">
        <button>
            IR AL MENÚ
        </button>
    </a>
    <a href="articulos.php"><button type="button">VOLVER A REGISTRO DE ARTÍCULOS</button></a>
    <a href="crearArticulo.php"><button type="button">CREAR UN ARTÍCULO</button></a>
    <a href="tablaArticulos.php"><button type="button">REFRESCAR</button></a>

    <!-- Encabezado de la página -->
    <h1>REGISTROS ALMACENADOS EN LA TABLA ARTÍCULOS.</h1>

    <!-- Tabla para mostrar los registros de la base de datos -->
    <div id="tabla-container">
        <table>
            <tr>
                <th>CÓDIGO</th>
                <th>NOMBRE</th>
                <th>GRUPO</th>
                <th>PESO</th>
                <th>VLR REPOSICIÓN</th>
                <th>CÓD. ALTERNO</th>
                <th>TIPO</th>
                <th>EDITAR</th>
                <th>ESTADO</th>
            </tr>

            <?php
            // Mostrar los registros en la tabla
            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $fila["codigo"] . "</td>";
                    echo "<td>" . $fila["articulo"] . "</td>";
                    echo "<td>" . $fila["grupo"] . "</td>";
                    echo "<td>" . $fila["peso"] . "</td>";
                    echo "<td>" . $fila["valorRepo"] . "</td>";
                    echo "<td>" . $fila["codAlterno"] . "</td>";
                    echo "<td>" . $fila["tipo"] . "</td>";
                    echo "<td>";
                    // Enlace para editar un registro
                    echo "<a href='editarArticulo.php?id=" . $fila["id"] . "'><button type='button'>EDITAR</button></a>";
                    echo "</td>";
                    echo "<td>" . $fila["estado"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>NO HAY REGISTROS EN LA TABLA.</td></tr>";
            }
            ?>

        </table>
    </div>

    <!-- Script para mostrar el mensaje de actualización -->
    <script>
        <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
            alert("REGISTRO ACTUALIZADO CORRECTAMENTE.");
        <?php endif; ?>
    </script>

</body>
</html>
