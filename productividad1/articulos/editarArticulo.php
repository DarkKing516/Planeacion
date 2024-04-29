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

// Obtener el ID del artículo a editar
if (isset($_GET['id'])) {
    $idArticulo = $_GET['id'];
    
    // Consulta para seleccionar el artículo por su ID
    $consulta = "SELECT * FROM articulos WHERE id = '$idArticulo'";
    $resultado = $conn->query($consulta);
    
    // Verificar si se encontró el artículo
    if ($resultado->num_rows > 0) {
        $articulo = $resultado->fetch_assoc();
    } else {
        echo "NO SE ENCONTRÓ EL ARTÍCULO.";
        exit();
    }
} else {
    // Si no se proporciona un ID de artículo, redirigir a la página de tablaArticulos.php
    header("Location: tablaArticulos.php");
    exit();
}

// Consulta para obtener los nombres de los grupos
$consultaGrupos = "SELECT nombre FROM grupos";
$resultadoGrupos = $conn->query($consultaGrupos);

// Consulta para obtener los nombres de los tipos
$consultaTipos = "SELECT nombre FROM tipos";
$resultadoTipos = $conn->query($consultaTipos);

// Proceso para actualizar los datos del artículo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST["codigo"];
    $articulo = $_POST["articulo"];
    $grupo = $_POST["grupo"];
    $peso = $_POST["peso"];
    $valorRepo = $_POST["valorRepo"];
    $tipo = $_POST["tipo"];
    $estado = $_POST["estado"];
    
    $actualizarArticulo = "UPDATE articulos SET codigo='$codigo', articulo='$articulo', grupo='$grupo', peso='$peso', valorRepo='$valorRepo', tipo='$tipo', estado='$estado' WHERE id='$idArticulo'";
    
    if ($conn->query($actualizarArticulo) === TRUE) {
        // Redirigir a la página de tablaArticulos.php con un parámetro de éxito
        header("Location: tablaArticulos.php?success=true");
        exit();
    } else {
        echo "ERROR AL ACTUALIZAR EL REGISTRO: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>EDITAR ARTÍCULO.</title>
 <!-- Estilos CSS -->
    <link rel="stylesheet" type="text/css" href="articulos.css">

</head>
<body>

<a href="/productividad/menu.php">
 <button>
     IR AL MENÚ
 </button>
</a>

<a href="tablaArticulos.php">
 <button type="button">VOLVER A LA TABLA DE ARTÍCULOS</button>
</a>

<h1>EDITAR ARTÍCULO.</h1>

<form id="updateForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $idArticulo); ?>">
 <input type="hidden" name="id" value="<?php echo $idArticulo; ?>">

 <label for="codigo" class="front-label">CÓDIGO:</label>
 <input type="text" id="codigo" name="codigo" value="<?php echo $articulo['codigo']; ?>" required>

 <label for="articulo" class="front-label">ARTÍCULO:</label>
 <input type="text" id="articulo" name="articulo" value="<?php echo $articulo['articulo']; ?>" required>

 <label for="grupo" class="front-label">GRUPO:</label>
 <select name="grupo" id="grupo" required>
 <?php
 // Mostrar opciones de grupo
 if ($resultadoGrupos->num_rows > 0) {
    while ($filaGrupo = $resultadoGrupos->fetch_assoc()) {
        $grupoSeleccionado = ($filaGrupo['nombre'] == $articulo['grupo']) ? 'selected' : '';
        echo "<option value='" . $filaGrupo['nombre'] . "' $grupoSeleccionado>" . $filaGrupo['nombre'] . "</option>";
    }
 }
 ?>
 </select>

 <label for="peso" class="front-label">PESO:</label>
 <input type="decimal" id="peso" name="peso" value="<?php echo $articulo['peso']; ?>" required>

 <label for="valorRepo" class="front-label">VALOR REPOSICIÓN:</label>
 <input type="text" id="valorRepo" name="valorRepo" value="<?php echo $articulo['valorRepo']; ?>" required>

 <label for="tipo" class="front-label">TIPO:</label>
 <select name="tipo" id="tipo" required>
 <?php
 // Mostrar opciones de tipo
 if ($resultadoTipos->num_rows > 0) {
    while ($filaTipo = $resultadoTipos->fetch_assoc()) {
        $tipoSeleccionado = ($filaTipo['nombre'] == $articulo['tipo']) ? 'selected' : '';
        echo "<option value='" . $filaTipo['nombre'] . "' $tipoSeleccionado>" . $filaTipo['nombre'] . "</option>";
    }
 }
 ?>
 </select>

 <label for="estado" class="front-label">ESTADO:</label>
        <select id="estado" name="estado" value="<?php echo $articulo['estado']; ?>" required>
            <option value="">SELECCIONE EL ESTADO</option>
                <option>ACTIVO</option>
                <option>INACTIVO</option>
        </select>

    <input type="submit" value="ACTUALIZAR" onclick="return confirmUpdate()">
</form>

<script type="text/javascript" src="articulos.js" defer></script>

<script>
    function confirmUpdate() {
        return confirm("ESTÁS SEGURO QUE QUIERES EDITAR ESTE ARTÍCULO?");
    }
</script>

</body>
</html>
