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
    <title>TABLA DE SALARIOS</title>
    <link rel="stylesheet" href="tablaSalarios.css"> <!-- Ajusta la ruta según la ubicación de tu archivo de estilos -->
</head>
<body>

<a href="salarios.php" class="salarios-link">
    <button class="salarios-button">
        VOLVER A GESTIÓN DE SALARIOS
    </button>
</a>

<a href="tablaSalarios.php" class="refresh-link">
    <button class="refresh-button">
        REFRESCAR
    </button>
</a>

<h1>TABLA DE SALARIOS</h1>

<table>
    <thead>
        <tr>
            <th>AÑO</th>
            <th>HORAS MENSUALES</th>
            <th>HORAS SEMANALES</th>
            <th>DESCRIPCIÓN</th>
            <th>SALARIO MÍNIMO</th>
            <th>AUXILIO DE TRANSPORTE</th>
            <th>CESANTÍAS</th>
            <th>INTERESES CESANTÍAS</th>
            <th>PRIMA</th>
            <th>VACACIONES</th>
            <th>SALUD EMPLEADO</th>
            <th>SALUD EMPLEADOR</th>
            <th>PENSIÓN EMPLEADO</th>
            <th>PENSIÓN EMPLEADOR</th>
            <th>CAJA DE COMPENSACIÓN</th>
            <th>ARL</th>
            <th>DOTACIÓN</th>
            <th>PORCENTAJE TOTAL</th>
            <th>COSTO MENSUAL</th>
            <th>COSTO HORA</th>
            <th>ACCIONES</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Conexión a la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "datosgenerales";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Consulta SQL para obtener todos los registros de la tabla salarios
        $sql = "SELECT * FROM salarios";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Generar las filas de la tabla con los datos de la base de datos
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["año"] . "</td>";
                echo "<td>" . $row["horas_mensuales"] . "</td>";
                echo "<td>" . $row["horas_semanales"] . "</td>";
                echo "<td>" . $row["descripcion"] . "</td>";
                echo "<td>" . $row["salario_minimo"] . "</td>";
                echo "<td>" . $row["auxilio_tte"] . "</td>";
                echo "<td>" . $row["cesantias"] . "</td>";
                echo "<td>" . $row["int_cesantias"] . "</td>";
                echo "<td>" . $row["prima"] . "</td>";
                echo "<td>" . $row["vacaciones"] . "</td>";
                echo "<td>" . $row["salud_empleado"] . "</td>";
                echo "<td>" . $row["salud_empleador"] . "</td>";
                echo "<td>" . $row["pension_empleado"] . "</td>";
                echo "<td>" . $row["pension_empleador"] . "</td>";
                echo "<td>" . $row["caja_compensacion"] . "</td>";
                echo "<td>" . $row["arl"] . "</td>";
                echo "<td>" . $row["dotacion"] . "</td>";
                echo "<td>" . $row["total_porcentaje"] . "</td>";
                echo "<td>" . $row["costo_mensual"] . "</td>";
                echo "<td>" . $row["costo_hora"] . "</td>";
                echo "<td>";
                echo "<button class='btn-eliminar' onclick='eliminarSalario(" . $row["id"] . ")'>ELIMINAR</button>";
                echo "<button class='btn-editar' onclick='editarSalario(" . $row["id"] . ")'>EDITAR</button>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='21'>NO HAY REGISTROS DISPONIBLES</td></tr>";
        }
        $conn->close();
        ?>
    </tbody>
</table>

<!-- Modal para la edición -->
<div id="modalEditar" class="modal">
  <div class="modal-contenido">
    <span class="cerrar" onclick="cerrarModalEditar()">&times;</span>
    <!-- Contenido del modal -->
    <h2>EDITAR SALARIO</h2>
    <!-- Formulario de edición -->
    <form id="formularioEditar">
        <label for="inputAño">AÑO:</label>
        <input type="text" id="inputAño" name="año"><br><br>
        
        <label for="inputHorasMensuales">HORAS MENSUALES:</label>
        <input type="text" id="inputHorasMensuales" name="horas_mensuales"><br><br>
        
        <label for="inputHorasSemanales">HORAS SEMANALES:</label>
        <input type="text" id="inputHorasSemanales" name="horas_semanales"><br><br>
        
        <label for="inputDescripcion">DESCRIPCIÓN:</label>
        <input type="text" id="inputDescripcion" name="descripcion"><br><br>
        
        <label for="inputSalarioMinimo">SALARIO MÍNIMO:</label>
        <input type="text" id="inputSalarioMinimo" name="salario_minimo"><br><br>
        
        <label for="inputAuxilioTte">AUXILIO DE TRANSPORTE:</label>
        <input type="text" id="inputAuxilioTte" name="auxilio_tte"><br><br>
        
        <label for="inputCesantias">CESANTÍAS:</label>
        <input type="text" id="inputCesantias" name="cesantias"><br><br>
        
        <label for="inputIntCesantias">INTERESES CESANTÍAS:</label>
        <input type="text" id="inputIntCesantias" name="int_cesantias"><br><br>
        
        <label for="inputPrima">PRIMA:</label>
        <input type="text" id="inputPrima" name="prima"><br><br>
        
        <label for="inputVacaciones">VACACIONES:</label>
        <input type="text" id="inputVacaciones" name="vacaciones"><br><br>
        
        <label for="inputSaludEmpleado">SALUD EMPLEADO:</label>
        <input type="text" id="inputSaludEmpleado" name="salud_empleado"><br><br>
        
        <label for="inputSaludEmpleador">SALUD EMPLEADOR:</label>
        <input type="text" id="inputSaludEmpleador" name="salud_empleador"><br><br>
        
        <label for="inputPensionEmpleado">PENSIÓN EMPLEADO:</label>
        <input type="text" id="inputPensionEmpleado" name="pension_empleado"><br><br>
        
        <label for="inputPensionEmpleador">PENSIÓN EMPLEADOR:</label>
        <input type="text" id="inputPensionEmpleador" name="pension_empleador"><br><br>
        
        <label for="inputCajaCompensacion">CAJA DE COMPENSACIÓN:</label>
        <input type="text" id="inputCajaCompensacion" name="caja_compensacion"><br><br>
        
        <label for="inputArl">ARL:</label>
        <input type="text" id="inputArl" name="arl"><br><br>
        
        <label for="inputDotacion">DOTACIÓN:</label>
        <input type="text" id="inputDotacion" name="dotacion"><br><br>
        
        <label for="inputTotalPorcentaje">PORCENTAJE TOTAL:</label>
        <input type="text" id="inputTotalPorcentaje" name="total_porcentaje"><br><br>
        
        <label for="inputCostoMensual">COSTO MENSUAL:</label>
        <input type="text" id="inputCostoMensual" name="costo_mensual"><br><br>
        
        <label for="inputCostoHora">COSTO HORA:</label>
        <input type="text" id="inputCostoHora" name="costo_hora"><br><br>
        
        <input type="hidden" id="inputID" name="id">
        
        <button type="submit">ACTUALIZAR</button>
    </form>
  </div>
</div>

<script src="tablaSalarios.js"></script>

</body>
</html>
