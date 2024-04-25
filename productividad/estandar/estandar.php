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
    <title>GESTIÓN DE ESTANDAR</title>
    <link rel="stylesheet" href="estandar.css">
    <script src="estandar.js"></script>
</head>
<body>

<!-- Botón "Ir al menú" fuera del formulario -->
<a href="/productividad/menu.php" class="boton-menu">IR AL MENÚ</a>

<!--<a href="importarEstandar.php" class="boton-importar">IMPORTAR ESTÁNDARES</a>-->

<form id="form-estandar">
    <?php
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

    // Obtener grupos
    $sql_grupos = "SELECT codigo, nombre FROM grupos";
    $result_grupos = $conn->query($sql_grupos);

    // Obtener procesos
    $sql_procesos = "SELECT codigo, nombre FROM procesos";
    $result_procesos = $conn->query($sql_procesos);

    // Obtener artículos
    $sql_articulos = "SELECT codigo, articulo FROM articulos";
    $result_articulos = $conn->query($sql_articulos);
    ?>

    <h2>INGRESAR ESTANDAR</h2>
    <div class="container" id="container-grupo">
        <label for="grupo" style="position: relative">GRUPO:</label><br>
        <input type="text" id="cod_grupo" name="cod_grupo" readonly style="width: 48%;"><br>
        <select id="grupo" name="grupo" required onchange="updateCodigoGrupo()" style="width: 48%; font-weight: bold; color: #244183; display: block; margin-bottom: 10px; text-align: center; position: relative; z-index: 2; padding: 10px; border: 2px solid #244183; border-radius: 15px;">
            <option value="">SELECCIONE UN GRUPO</option>
            <?php
            if ($result_grupos->num_rows > 0) {
                while($row = $result_grupos->fetch_assoc()) {
                    echo "<option value='".$row["codigo"]."'>".$row["nombre"]."</option>";
                }
            }
            ?>
        </select>
    </div><br>

    <div class="container" id="container-proceso">
        <label for="proceso" style="position: relative">PROCESO:</label><br>
        <input type="text" id="cod_proceso" name="cod_proceso" readonly style="width: 48%;"><br>
        <select id="proceso" name="proceso" required onchange="updateCodigoProceso()" style="width: 48%; font-weight: bold; color: #244183; display: block; margin-bottom: 10px; text-align: center; position: relative; z-index: 2; padding: 10px; border: 2px solid #244183; border-radius: 15px;">
            <option value="">SELECCIONE UN PROCESO</option>
            <?php
            if ($result_procesos->num_rows > 0) {
                while($row = $result_procesos->fetch_assoc()) {
                    echo "<option value='".$row["codigo"]."'>".$row["nombre"]."</option>";
                }
            }
            ?>
        </select>
    </div><br>

    <div class="container" id="container-articulo">
        <label for="articulo">ARTÍCULO:</label><br>
        <input type="text" id="cod_articulo" name="cod_articulo" readonly style="width: 48%;"><br>
        <select id="articulo" name="articulo" required onchange="updateCodigoArticulo(), obtenerPeso()" style="width: 48%; font-weight: bold; color: #244183; display: block; margin-bottom: 10px; text-align: center; position: relative; z-index: 2; padding: 10px; border: 2px solid #244183; border-radius: 15px;">
            <option value="">SELECCIONE UN ARTÍCULO</option>
            <?php
            if ($result_articulos->num_rows > 0) {
                while($row = $result_articulos->fetch_assoc()) {
                    echo "<option value='".$row["codigo"]."'>".$row["articulo"]."</option>";
                }
            }
            ?>
        </select><br>
        <label for="peso">PESO:</label><br>
        <input type="decimal" id="peso" name="peso" required readonly><br>
    </div><br>

    <div class="container" id="container-estandar">
        <label for="codigo_estandar">CÓD. ESTÁNDAR:</label><br>
        <input type="text" id="codigo_estandar" name="codigo_estandar" readonly ><br>
        <label for="nombre_estandar">NOM. ESTÁNDAR:</label><br>
        <input type="text" id="nombre_estandar" name="nombre_estandar" readonly><br>
    </div><br>

    <div id="container-tiempo-estandar">
        <label for="t_estandar">TIEMPO ESTÁNDAR (MINUTOS):</label><br>
        <input type="number" id="t_estandar" name="t_estandar" required min="0.1" step="0.1" placeholder="EJ: 1.5">
    </div>

    <button type="button" class="boton-guardar" onclick="guardarEstandar()">GUARDAR</button>
</form>

<!-- Mostrar registros almacenados -->
<h2>REGISTROS ALMACENADOS EN LA TABLA ESTANDAR</h2>
<table>
    <tr>
        <th>CÓD. GRUPO</th>
        <th>NOM. GRUPO</th>
        <th>CÓD. PROCESO</th>
        <th>NOM. PROCESO</th>
        <th>CÓD. ARTÍCULO</th>
        <th>NOM. ARTÍCULO</th>
        <th>PESO</th>
        <th>NOM. ESTANDAR</th>
        <th>T. ESTANDAR (MIN)</th>
        <th>CÓD ESTANDAR</th>
        <th>ACCIONES</th>
    </tr>
    <?php
    // Conexión a la base de datos (reemplaza los valores con los de tu configuración)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "datosgenerales";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $sql = "SELECT e.cod_grupo, g.nombre AS grupo_nombre, e.cod_proceso, p.nombre AS proceso_nombre, e.cod_articulo, a.articulo AS articulo_nombre, e.peso, e.nombre_estandar, e.t_estandar, e.cod_estandar FROM estandar e INNER JOIN grupos g ON e.cod_grupo = g.codigo INNER JOIN procesos p ON e.cod_proceso = p.codigo INNER JOIN articulos a ON e.cod_articulo = a.codigo";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["cod_grupo"] . "</td>";
            echo "<td>" . $row["grupo_nombre"] . "</td>";
            echo "<td>" . $row["cod_proceso"] . "</td>";
            echo "<td>" . $row["proceso_nombre"] . "</td>";
            echo "<td>" . $row["cod_articulo"] . "</td>";
            echo "<td>" . $row["articulo_nombre"] . "</td>";
            echo "<td>" . $row["peso"] . "</td>"; // Aquí se muestra el peso
            echo "<td>" . $row["nombre_estandar"] . "</td>";
            echo "<td>" . $row["t_estandar"] . "</td>";
            echo "<td>" . $row["cod_estandar"] . "</td>";
            echo "<td><button class='boton-eliminar' onclick='eliminarRegistro(\"" . $row["cod_estandar"] . "\")'>ELIMINAR</button><br>";
            echo "<a href='editarEstandar.php?codigo=" . $row["cod_estandar"] . "' class='btn-editar'>EDITAR</a>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='9'>NO HAY REGISTROS ALMACENADOS.</td></tr>";
    }
    $conn->close();
    ?>
</table>

</body>
</html>
