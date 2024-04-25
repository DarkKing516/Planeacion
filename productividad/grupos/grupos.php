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
    <title>GESTIÓN DE GRUPOS.</title>
    <link rel="stylesheet" href="grupos.css">
</head>     
<body>
    <a href="/productividad/menu.php">
        <button>IR AL MENÚ</button>
    </a>
    <a href="grupos.php"><button type="button">REFRESCAR</button></a>
       
    <h1>GESTIÓN DE GRUPOS.</h1>

    <!-- Modal para editar -->
    <div id="modalEditar" class="modal">
        <div class="modal-contenido">
            <span class="cerrar" onclick="cerrarModalEditar()">&times;</span>
            <div id="formularioEditar"></div>
        </div>
    </div>

    <form id="formulario" action="guardarGrupo.php" method="post">
        <!-- Placeholder dinámico para el código -->
        <label class="front-label" for="codigo">CÓDIGO:</label>
        <input type="text" id="codigo" value="codigo" readonly >
        <label class="front-label" for="nombre">NOMBRE:</label>
        <input type="text" id="nombre" name="nombre" placeholder="INGRESE EL NOMBRE" required>
        <!-- Cambiado el tipo de input a button para conservar el estilo -->
        <button type="button" onclick="guardarGrupo()">GUARDAR</button>

        <?php
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
            // Obtener el último código y autoincrementar
            $sql = "SELECT MAX(codigo) as max_codigo FROM grupos";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $codigoGenerado = str_pad(((int)$row['max_codigo']) + 1,2,'0', STR_PAD_LEFT);
        ?>                    
    <script>
    // Obtén el valor generado de código desde PHP y asigna al input existente
    document.getElementById("codigo").value = "<?php echo $codigoGenerado; ?>";
    </script>
    </form>

    <!-- Tabla de registros almacenados en la tabla grupos -->
    <h1>REGISTROS ALMACENADOS EN LA TABLA GRUPOS.</h1>
    <?php
        // Consultar los registros almacenados en la tabla 'grupos'
        $sql = "SELECT codigo, nombre FROM grupos";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Mostrar los registros en forma de tabla
            echo "<table>";
            echo "<tr><th>CÓDIGO</th><th>NOMBRE</th><th>ACCIONES</th></tr>";        
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["codigo"]. "</td>";
                echo "<td>" . $row["nombre"]. "</td>";
                echo "<td>";
                echo "<a href='#' onclick='eliminarGrupo(" . $row["codigo"] . ")' class='btn-eliminar'>ELIMINAR</a>";
                echo "<a href='#' onclick='abrirModalEditar(" . $row["codigo"] . ")' class='btn-editar'>EDITAR</a>";
                echo "</td>";
                echo "</tr>"; 
            }
            echo "</table>";
        } else {
            echo "NO HAY REGISTROS ALMACENADOS EN LA TABLA.";
        }

        // Cerrar la conexión
        $conn->close();
    ?>
    <!-- Incluyendo el archivo JavaScript -->
    <script src="grupos.js"></script>
</body>
</html>
