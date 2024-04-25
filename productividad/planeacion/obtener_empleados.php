<?php
// Verificar si se recibió la sede seleccionada
if(isset($_POST['sede'])) {
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
    
    // Obtener la sede seleccionada
    $sede = $_POST['sede'];
        
    // Consulta SQL para obtener los empleados de la sede seleccionada
    $sql_empleados = "SELECT DISTINCT cedula, nombre FROM empleados WHERE sede = '$sede'";
    $result = $conn->query($sql_empleados);
    
    if ($result->num_rows > 0) {
        echo "<form action='procesar_formulario.php' method='post'>";
        echo "<input type='hidden' name='sede' value='$sede'>";
        echo "<table>";
        echo "<thead>
                <tr>
                    <th>N°</th>
                    <th>CEDULA</th>
                    <th>NOM. EMPLEADO</th>
                    <th>GRUPO</th>
                    <th>COD. ARTÍCULO</th>
                    <th>ARTÍCULO</th>
                    <th>NOM. ESTANDAR</th>
                    <th>T. ESTANDAR</th>
                    <th>PESO ART.</th>
                    <th>H. DIA</th>
                    <th>MIN. DIA</th>
                    <th>T. DEL DIA</th>
                    <th>PESO TOTAL (KG)</th>
                    <th>OBSERVACIONES</th>
                </tr>
            </thead>";
        echo "<tbody>";

        $contador = 1;
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $contador++ . "</td>";
            echo "<td><input type='text' name='cedula[]' value='" . $row["cedula"] . "'></td>";
            echo "<td><input type='text' name='nombre[]' value='" . $row["nombre"] . "'></td>";
            echo "<td><select name='grupo[]' class='grupo'></select></td>";
            echo "<td><input type='text' name='cod_articulo' id='cod_articulo' value=''></td>";
            echo "<td><select name='articulo[]' class='articulo'></select></td>";
            echo "<td><select name='nombre_estandar[]' class='nombre_estandar'></select></td>";
            echo "<td><input type='text' name='t_estandar' id='t_estandar' value=''></td>";
            echo "<td><input type='text' name='peso_articulo' id='peso_articulo' value=''></td>";
            echo "<td><input type='number' name='horas_dia[]' class='horas_dia' data-index='$contador' id='horas_dia_$contador' oninput=\"calcularMinutos($contador)\"></td>";
            echo "<td><input type='number' name='minutos_dia[]' class='minutos_dia' readonly id='minutos_dia_$contador'></td>";
            echo "<td><input type='number' name='tarea_dia[]' class='tarea_dia' readonly id='tarea_dia_$contador'></td>";
            echo "<td><input type='text' name='peso_total[]' class='peso_total' readonly id='peso_total_$contador'></td>";
            echo "<td><input type='text' name='observacion[]' class='observacion' id='observacion_$contador'></td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";        
        echo "</form>";
    } else {
        // Si no se encontraron empleados para la sede seleccionada, mostrar un mensaje de error
        echo "No se encontraron empleados para la sede seleccionada.";
    }
    
    // Cerrar la conexión
    $conn->close();
} else {
    // Si no se proporcionó la sede seleccionada, mostrar un mensaje de error
    echo "Error: No se proporcionó la sede seleccionada.";
}
?>
