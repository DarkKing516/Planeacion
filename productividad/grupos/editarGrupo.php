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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDITAR GRUPO</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>EDITAR GRUPO</h1>

    <?php
    // Verificar si se recibió el código del grupo a editar
    if(isset($_GET['codigo'])) {
        // Obtener el código del grupo desde la URL
        $codigoGrupo = $_GET['codigo'];

        // Conexión a la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "datosgenerales";

        // Crear conexión a la base de datos
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Verificar la conexión
        if ($conn->connect_error) {
            die("CONEXIÓN FALLIDA: " . $conn->connect_error);
        }

        // Consultar la información del grupo con el código recibido
        $sql = "SELECT codigo, nombre FROM grupos WHERE codigo = $codigoGrupo";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Mostrar el formulario para editar el grupo
            $row = $result->fetch_assoc();
            ?>
            <form action="actualizarGrupo.php" method="post" onsubmit="return confirm('¿ESTÁS SEGURO QUE QUIERES EDITAR ESTE GRUPO?');">
                <input type="hidden" name="codigo" value="<?php echo $row['codigo']; ?>">
                <label for="nombre">NOMBRE:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $row['nombre']; ?>" required>
                <button type="submit">ACTUALIZAR</button>
            </form>
            <?php
        } else {
            echo "NO SE ENCONTRÓ NINGÚN GRUPO CON EL CÓDIGO $codigoGrupo.";
        }

        // Cerrar la conexión
        $conn->close();
    } else {
        echo "NOSE RECIBIÓ EL CÓDIGO DEL GRUPO A EDITAR.";
    }
    ?>

</body>
</html>
