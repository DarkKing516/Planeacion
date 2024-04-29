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

// Procesar los datos del formulario si se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $principal = $_POST["principales"];
    $nodo = $_POST["nodo"];
    $modulo = $_POST["modulo"];
    $rol = $_POST["rol"];
    
    // Crear la consulta SQL para insertar los datos en la tabla permisos
    $sql = "INSERT INTO permisos (principales, nodo, modulo, rol) VALUES ('$principal', '$nodo', '$modulo', '$rol')";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        echo "";
    } else {
        echo "ERROR AL GUARDAR LOS DATOS: " . $conn->error;
    }
}

// Función para obtener las opciones de la tabla 'roles'
function getRolesOptions() {
    global $conn;
    $options = array();
    // Consulta SQL para obtener las opciones de la tabla 'roles'
    $sql = "SELECT nombre FROM roles";
    $result = $conn->query($sql);
    // Almacena las opciones en un arreglo
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $options[] = $row["nombre"];
        }
    }
    return $options;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GESTIÓN DE PERMISOS</title>
<link rel="stylesheet" href="permisos.css">
</head>
<body>

<a href="/productividad/menu.php" class="menu-link">
        <button class="menu-button">IR AL MENÚ</button>
    </a>

  <h1>GESTIÓN DE PERMISOS</h1>
  <form id="permisosForm" class="form-container" method="post" action="permisos.php">
        <label for="principales">PRINCIPALES:</label>
    <select id="principales" name="principales" class="form-select">
      <option value="" selected>SELECCIONE UNA OPCIÓN</option>
      <option value="configMaestras">CONFIGURACIÓN DE MAESTRAS</option>
      <option value="planeacion">PLANEACIÓN DIARIA</option>
      <option value="produccion">PRODUCCIÓN DIARIA</option>
      <option value="informes">INFORMES</option>
    </select>

    <label for="nodo">NODO:</label>
    <select id="nodo" name="nodo" class="form-select">
      <option value="" disabled selected>SELECCIONE UNA OPCIÓN</option>
      <!-- Este select se llenará automáticamente según la selección del primer select -->
    </select>

    <label for="modulo">MODULO:</label>
    <select id="modulo" name="modulo" class="form-select">
      <option value="" selected>SELECCIONE UNA OPCIÓN</option>
      <!-- Este select se llenará automáticamente según la selección del segundo select -->
    </select>

    <div class="form-group">
        <label for="rol">ROL:</label>
        <select id="rol" name="rol" class="form-control">
            <option value="" disabled selected>SELECCIONE UN ROL</option>
            <?php 
            $roles_options = getRolesOptions();
            foreach ($roles_options as $option): 
            ?>
                <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" id="guardarBtn" class="btn">GUARDAR</button>
  </form>

  <?php
// Consultar los datos almacenados en la tabla permisos
$sql_permisos = "SELECT principales, nodo, modulo, rol FROM permisos";
$result_permisos = $conn->query($sql_permisos);

// Imprimir el encabezado de la tabla
echo "<h1>DATOS ALMACENADOS EN LA TABLA PERMISOS</h1>";
echo "<table>";
echo "<tr><th>PRINCIPALES</th><th>NODO</th><th>MÓDULO</th><th>ROL</th></tr>";

// Verificar si se encontraron resultados
if ($result_permisos->num_rows > 0) {
    // Imprimir los resultados en una tabla
    while ($row = $result_permisos->fetch_assoc()) {
        echo "<tr><td>".$row["principales"]."</td><td>".$row["nodo"]."</td><td>".$row["modulo"]."</td><td>".$row["rol"]."</td></tr>";
    }
} else {
    echo "<tr><td colspan='4'>NO HAY DATOS ALMACENADOS EN LA TABLA PERMISOS</td></tr>";
}
echo "</table>";
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="permisos.js"></script>
</body>
</html>
