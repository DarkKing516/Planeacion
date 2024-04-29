<?php
// Iniciar sesión
session_start();

if (!isset($_SESSION['usuario'])) {
    echo "<script>alert('DEBES INICIAR SESIÓN PARA PODER INGRESAR A ESTA PÁGINA');</script>";      
    header("Location: /productividad/index.php");
    exit(); 
}
// Guardar el nombre de usuario en una variable de sesión
$usuario_actual = $_SESSION['usuario'];

// Conexión a la base de datos (reemplaza los valores con los de tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datosgenerales";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener empleados
$sql_empleados = "SELECT DISTINCT sede FROM empleados";
$result_empleados = $conn->query($sql_empleados);

// Obtener empleados
$sql_usuario = "SELECT DISTINCT usuario FROM usuario";
$result_usuario = $conn->query($sql_usuario);

$sql_articulos = "SELECT DISTINCT cod_articulo, articulo FROM estandar";
$result_articulos = $conn->query($sql_articulos);

$sql_estandar = "SELECT nombre_estandar, t_estandar FROM estandar";
$result_estandar = $conn->query($sql_estandar);

// Obtener las opciones disponibles para el campo "SEDE" de la tabla "descansos2"
$sede_options2_query = "SELECT DISTINCT sede FROM descansos";
$sede_options2_result = $conn->query($sede_options2_query);

// Array para almacenar las opciones de sede
$sede_options2 = array();

if ($sede_options2_result->num_rows > 0) {
    while ($row = $sede_options2_result->fetch_assoc()) {
        $sede_options2[] = $row['sede'];
    }
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTIÓN DE PRODUCCION</title>
    <link rel="stylesheet" href="produccion.css">   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="produccion.js"></script>
</head>
<body>
<!-- Botón "Ir al menú" fuera del formulario -->
<a href="/productividad/menu.php" class="boton-menu">
    IR AL MENÚ
</a>

<a href= "tablaProduccion.php" class="boton-menu">
    IR A LA TABLA PRODUCCIÓN
</a>

<!-- Título -->
<h1>GESTIÓN DE PRODUCCION</h1>

<!-- Contenedor para SEDE, USUARIO y FECHA -->
<div class="container-info">
    <form id="" method="POST" action="guardarProduccion.php">
        <div class="container-izquierda">
            <label for="sede">SEDE:</label><br>        
            <select id="sede" name="sede" required>
                <option value="" disabled selected>SELECCIONE UNA SEDE</option>
                <?php
                if ($result_empleados->num_rows > 0) {
                    while($row = $result_empleados->fetch_assoc()) {
                        echo "<option value='".$row["sede"]."'>".$row["sede"]."</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="container-central">
            <label for="usuario">USUARIO:</label><br>        
            <input type="text" id="usuario" name="usuario" value="<?php echo $usuario_actual; ?>" readonly>
        </div>

        <div class="container-derecha">
            <div id="container-tiempo-estandar">
                <label for="fecha">FECHA PLANEACIÓN:</label><br>
                <input type="date" id="fecha" name="fecha" placeholder="DD/MM/AAAA" style="text-transform: uppercase;" required>
            </div>
        </div>

        <div class="empleado">
        <label class="label-cedula">CÉDULA EMPLEADO:</label><br>
    <input type="number" id="cedula" name = "cedula" readonly required>
    
    <label class="label-empleado">NOMBRE DEL EMPLEADO:</label><br>
    <select id="empleados" name="empleado" required>
        <option value="" disabled selected>SELECCIONE UN EMPLEADO</option>
    </select>
    </div>
</div>

<!-- Formulario principal -->
<div class="container-form">
    <form id="form-produccion" method="POST" action="guardarProduccion.php">
            <div id="formulario">
                <div id="conjunto-existente" class="conjunto">
                    <div class="grupo">
                        <label for="cod_grupo">CÓDIGO GRUPO:</label>
                        <input type="number" id="cod_grupo" name="cod_grupo" readonly required><br>

                        <label for="grupo">NOMBRE GRUPO:</label>
                        <select id="grupo" name="grupo" required>
                            <option value="" disabled selected>SELECCIONE UN GRUPO</option>
                                <?php
                                // Recorre los grupos obtenidos de la base de datos y muestra sus nombres como opciones
                                if ($result_grupos->num_rows > 0) {
                                    while($row = $result_grupos->fetch_assoc()) {
                                        echo "<option value='".$row["grupo"]."'>".$row["grupo"]."</option>";
                                    }
                                }
                                ?>
                        </select>
                    </div><br>

                    <div class="articulo">
    <label for="cod_articulo">CÓDIGO ARTÍCULO:</label>
    <input type="number" id="cod_articulo" name="cod_articulo" readonly required><br>

    <label for="articulo">BUSCAR ARTÍCULO:</label><br>
    <input type="text" id="articulo" name="articulo" list="lista_articulos" oninput="actualizarCodArticulo(); obtenerNombresEstandar();">
    <datalist id="lista_articulos">
        <?php
        // Recorre los artículos obtenidos de la base de datos y muestra sus nombres como opciones
        if ($result_articulos->num_rows > 0) {
            while($row = $result_articulos->fetch_assoc()) {
                echo "<option value='".$row["articulo"]."' data-cod='".$row["cod_articulo"]."'></option>";
            }
        }
        ?>
    </datalist>
    </div><br>
                    <div class="estandar">
                        <label for="t_estandar">TIEMPO ESTÁNDAR:</label>
                        <input type="decimal" id="t_estandar" name="t_estandar" readonly required><br>

                        <label for="nombre_estandar">NOMBRE ESTÁNDAR:</label>
                        <select id="nombre_estandar" name="nombre_estandar" >
                        <option value="" disabled select>SELECCIONE UN ESTANDAR</option>
                        <?php
                        if ($result_estandar->num_rows > 0) {
                            while($rows =$result_estandar-> fetch_assoc ()) {
                                echo "<option value='".$row["t_estandar"]."'>".$row["nombre_estandar"]."</option>";
                            }
                        }
                        ?>
                    </select>                                      
                    </div>

                    <h1>______________________________________________________________</h1>

                    <div class="hora">
                        <label for="hora_inicio">HORA INICIO:</label>
                        <input type="time" id="hora_inicio" name="hora_inicio" required><br>

                        <label for="hora_fin">HORA FIN:</label>
                        <input type="time" id="hora_fin" name="hora_fin" required><br>

                        <label for="tiempoLaborado">TIEMPO (MIN):</label>
                        <input type="text" id="tiempoLaborado" name="tiempoLaborado" readonly required>
                    </div><br><br>

                    <div class="descansos">
                        <label><input type="checkbox" id="descanso1_checkbox" name="descanso1"> DESCANSO 1:</label>
                        <input type="text" id="descanso1_input" name="descanso1_input" value="15" readonly>

                        <label><input type="checkbox" id="descanso2_checkbox" name="descanso2"> DESCANSO 2:</label>
                        <input type="text" id="descanso2_input" name="descanso2_input" value="15" readonly>

                        <label><input type="checkbox" id="almuerzo_checkbox" name="almuerzo"> ALMUERZO:</label>
                        <input type="text" id="almuerzo_input" name="almuerzo_input" readonly>
                    </div><br><br>

                    <div class="a">
                        <label for="totalDescanso">T. DESCANSOS (MIN):</label>
                        <input type="text" id="totalDescanso" name="totalDescanso" readonly><br><br>

                        <label for="tiempoReal">T. REAL (MIN):</label>
                        <input type="text" id="tiempoReal" name="tiempoReal" readonly><br><br>
                        
                        <label for="horasExtra">H. EXTRA (MIN):</label>
                        <input type="decimal" id="horasExtra" name="horasExtra" class="input-horas-extra" readonly required><br><br>
                    </div>

                    <h1>______________________________________________________________</h1>

                    <div class="b">
                        <label for="eq_disponible">EQUIPO DISPONIBLE:</label>
                        <input type="number" id="eq_disponible" name="eq_disponible" required>

                        <label for="eq_taller">EQUIPO EN TALLER:</label>
                        <input type="number" id="eq_taller" name="eq_taller" required>
                    </div><br>

                    <div class="c">
                        <label for="observaciones">OBSERVACIONES:</label>
                        <textarea id="observaciones" name="observaciones" rows="4"></textarea>
                    </div>
                </div>
            <h1>______________________________________________________________</h1>
            </div>

            <input type="submit" value="GUARDAR" class="boton-guardar">

            <!--<button type="button" id="boton-mas">+</button>-->
        </div>
        </div>
    </form>
</div>    
</body>
</html>
