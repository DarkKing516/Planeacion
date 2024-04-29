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
    <link rel="stylesheet" href="salarios.css">
</head>
<body>

<a href="/productividad/menu.php" class="menu-link">
    <button class="menu-button">
        IR AL MENÚ
    </button>
</a>

<a href="tablaSalarios.php" class="tabla-link">
    <button class="tabla-button">
        IR A LA TABLA DE SALARIOS
    </button>
</a>

    <h1>TABLA DE SALARIOS.</h1>
    <table>
        <tr>
            <th>AÑO</th>
            <th>HORAS MENSUALES</th>
            <th>HORAS SEMANALES</th>
            <th>DESCRIPCIÓN</th>
        </tr>
        <tr>
            <td><input type="number" id="año" name="año" value="2024" required min="1900" max="2100"></td>
            <td><input type="number" id="horas_mensual" name="horas_mensual" value=""readonly change></td>
            <td><input type="number" id="horas_semanal" name="horas_semanal" required step="1"></td>
            <td><input type="text" id="description" name="description"></td>
        </tr>
    </table>
    <!--tabla de salarios, prestaciones, parafiscales y costos -->
    <table>
        <tr>
            <th>SALARIOS </th>
            <th>PORCENTAJE</th>
            <th>VALOR</th>
        </tr>
        <tr>
            <td>SALARIO MÍNIMO LEGAL VIGENTE</td>
            <td>100%</td>
            <td><input type="number" id="salario" name="salario" value="" <?php echo date('Y') == 2024 ?  : '';?>change></td>
        <tr>
            <td>AUXILIO DE TRANSPORTE</td>
            <td>100%</td>
            <td><input type="number" id="aux_tte" name="aux_tte" value=" " <?php echo date('Y') == 2024 ?  : '';?>change></td>
        </tr>  
        <tr>
            <th>PRESTACIONES</th>
            <th></th>
            <th>VALOR</th>
        </tr>   
        <tr>
            <td>CESANTÍAS</td>
            <td>8.33%</td>
            <td><input type="number" id="cesantias" name="cesantias" value="<?php echo obtenerCesantias(); ?>" readonly ></td>
        </tr>
        <tr>
            <td>INTERESES A LAS CESANTÍAS</td>
            <td>1%</td>
            <td><input type="number" id="int_cesantias" name="int_cesantias" value="" readonly></td>
        </tr>
        <tr>
            <td>PRIMA</td>
            <td>8.33%</td>
            <td><input type="number" id="prima" name="prima" value=" "  readonly ></td>
        </tr>
        <tr>
            <td>VACACIONES</td>
            <td>4.17%</td>
            <td><input type="number" id="vacaciones" name="vacaciones" value=" "  readonly ></td>
        </tr>
        <tr>
            <th>PARAFISCALES</th>
            <th></th>
            <th>VALOR EMPLEADO</th>
            
        </tr>
        <tr>
            <td>SALUD EMPLEADO (este valor no se suma al total puesto que no lo asume el empleador)</td>
            <td>4%</td>            
            <td><input type="number" id="salud" name="salud" value=""  readonly ></td>
            
        </tr>
        <tr>
            <th>SALUD EMPLEADOR (somos aportantes exonerados de apórtes a salud, este valor es informativo)</th>
            <td>8.5%</td>
            <td><input type="number" id="salud_empleador" name="salud_empleador" value=""  readonly change></td>
        
        <tr>
        <tr>
            <td>PENSIÓN EMPLEADO (este valor no se suma al total puesto que no lo asume el empleador)</td>
            <td>4%</td>
            <td><input type="number" id="pension" name="pension" value=" "  readonly ></td>
        </tr>
        <tr>
            <td>PENSIÓN EMPLEADOR</td>            
            <td>12%</td>
            <td><input type="number" id="pension_empleador" name="pension_empleador" value=" "  readonly change></td>
        </tr>
        <tr>
            <td>CAJA DE COMPENSACIÓN</td>                       
            <td>4%</td>
            <td><input type="number" id="caja_compensacion" name="caja_compensacion" value=" "  readonly change></td>
        </tr> 
        <tr>
            <td>ARL</td>                       
            <td>6.96%</td>
            <td><input type="number" id="arl" name="arl" value=" "  readonly change></td>
        </tr>   
        <tr>
            <td>DOTACION (valor promedio mensual con una base de 160.000 por empleado cada 4 meses)</td>                       
            <td>3%</td>
            <td><input type="number" id="dotacion" name="dotacion" value=" "  readonly change></td>
        </tr>         
        <tr>
            <td>TOTAL PORCENTAJE PRESTACIONAL SAECO (este porcentaje es estimado, puede variar segun el nivel de riesgo de cada cargo)</td>                       
            <td>47,7%</td>
            <td><input type="number" id="total_porcentaje" name="total_porcentaje" value=" "  readonly change></td>
        </tr>  
        <tr>
            <th>COSTOS</th>
            <th></th>
            <th>VALOR</th>
        </tr>
        <tr>
            <td>COSTO MENSUAL (corresponde al total prestacional de SAECO mas el auxilio de transporte)</td>
            <td></td>            
            <td><input type="number" id="costo_mensual" name="costo_mensual" value=""readonly change></td>
        </tr>
        <tr>
            <td>COSTO HORA</td>
            <td></td>             
            <td><input type="number" id="costo_hora" name="costo_hora" value=" " readonly change></td>
        </tr>
    </table>
    
    <button type="button" class="boton-guardar" onclick="guardarsalarios()">GUARDAR</button>

<script src="salarios.js"></script>
</body>
</html>

<?php
function obtenerCesantias() {
    // Código PHP para obtener el valor de las cesantías
}
?>
