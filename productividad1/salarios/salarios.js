document.getElementById('horas_semanal').addEventListener('input', function() {
    var horas_semanal = parseFloat(document.getElementById('horas_semanal').value);
    var horas_mensual = horas_semanal * 51.5 / 12; // Asumiendo 52 semanas en un año
    document.getElementById('horas_mensual').value = horas_mensual.toFixed(2);
});
// Oyente para el cambio de salario

var salarioInput = document.getElementById('salario');
var cesantiasInput = document.getElementById('cesantias');
var int_cesantiasInput = document.getElementById('int_cesantias');
var primaInput = document.getElementById('prima');
var vacacionesInput = document.getElementById('vacaciones');
var saludInput = document.getElementById('salud');
var salud_empleadorInput = document.getElementById('salud_empleador');
var pensionInput = document.getElementById('pension');
var pension_empleadorInput = document.getElementById('pension_empleador');
var caja_compensacionInput = document.getElementById('caja_compensacion');
var arlInput = document.getElementById('arl');
var dotacionInput = document.getElementById('dotacion');
var porcentajeTotalInput = document.getElementById('total_porcentaje');

salarioInput.addEventListener('change', function() {
    var salarioMinimo = parseFloat(this.value);

    if (!isNaN(salarioMinimo)) {
        // Cesantías
        var porcentajeCesantias = 8.33 / 100;
        cesantiasInput.value = salarioMinimo * porcentajeCesantias;

        // Intereses a las cesantías
        var porcentajeint_cesantias = 1;
        int_cesantiasInput.value = salarioMinimo * porcentajeint_cesantias / 100;

        // Prima
        var porcentajePrima = 8.33 / 100;
        primaInput.value = salarioMinimo * porcentajePrima;

        // Vacaciones
        var porcentaje_vacaciones = 4.17 / 100;
        vacacionesInput.value = salarioMinimo * porcentaje_vacaciones;

        // Salud
        var porcentaje_salud = -4 / 100;
        saludInput.value = salarioMinimo * porcentaje_salud;

        // Salud empleador
        var porcentaje_salud_empleador = 8.5 / 100;
        salud_empleadorInput.value = Math.floor(salarioMinimo * porcentaje_salud_empleador);

        // Pensión empleado
        var porcentaje_pension = -4 / 100;
        pensionInput.value = salarioMinimo * porcentaje_pension;

        // Pensión empleador
        var porcentaje_pension_empleador = 12 / 100;
        pension_empleadorInput.value = salarioMinimo * porcentaje_pension_empleador;

        // Caja de compensación
        var porcentaje_caja_compensacion = 4 / 100;
        caja_compensacionInput.value = salarioMinimo * porcentaje_caja_compensacion;

        // ARL
        var porcentaje_arl = 6.96 / 100;
        arlInput.value = salarioMinimo * porcentaje_arl;

        // Dotación
        dotacionInput.value = (160000 / 12) * 3;

        // Porcentaje total
        var porcentaje = 47.73 / 100;
        porcentajeTotalInput.value = salarioMinimo * (1 + porcentaje);

                 
    }
});


document.addEventListener("DOMContentLoaded", function() {
    // Event listener para el cambio de horas mensuales
    document.getElementById('horas_mensual').addEventListener('input', function() {
        var costo_mensual = parseFloat(document.getElementById('costo_mensual').value);
        var horas_mensual = parseFloat(this.value);
        
        if (!isNaN(costo_mensual) && !isNaN(horas_mensual) && horas_mensual !== 0) {
            document.getElementById('costo_hora').value = (costo_mensual / horas_mensual).toFixed(2);
        } else {
            document.getElementById('costo_hora').value = ''; // Limpiar el campo si los valores no son válidos
        }
    });

    // Event listener para el cambio de salario
    document.getElementById('salario').addEventListener('change', function() {
        var salarioMinimo = parseFloat(this.value);

        if (!isNaN(salarioMinimo)) {
            // Cálculos de prestaciones, parafiscales y costos
            // Coloca aquí los cálculos que ya tenías
        }
    });

    // Event listener para el cambio de auxilio de transporte
    document.getElementById('aux_tte').addEventListener('change', function() {
        var aux_tte = Math.floor(this.value);
        var total_porcentaje = Math.floor(document.getElementById('total_porcentaje').value);

        if (!isNaN(aux_tte) && !isNaN(total_porcentaje)) {
            document.getElementById('costo_mensual').value = aux_tte + parseFloat(total_porcentaje.toFixed(0));
            
            // Recalculamos el costo por hora si el valor de horas mensuales no es cero
            var horas_mensual = parseFloat(document.getElementById('horas_mensual').value);
            if (!isNaN(horas_mensual) && horas_mensual !== 0) {
                document.getElementById('costo_hora').value = (document.getElementById('costo_mensual').value / horas_mensual).toFixed(2);
            }
        }
    });
});


function guardarsalarios() {
    // Obtener los valores de los campos del formulario
    var año = document.getElementById('año').value;
    var horas_mensuales = document.getElementById('horas_mensual').value;
    var horas_semanales = document.getElementById('horas_semanal').value;
    var descripcion = document.getElementById('description').value;
    var salario_minimo = document.getElementById('salario').value;
    var auxilio_tte = document.getElementById('aux_tte').value;
    var cesantias = document.getElementById('cesantias').value;
    var int_cesantias = document.getElementById('int_cesantias').value;
    var prima = document.getElementById('prima').value;
    var vacaciones = document.getElementById('vacaciones').value;
    var salud_empleado = document.getElementById('salud').value;
    var salud_empleador = document.getElementById('salud_empleador').value;
    var pension_empleado = document.getElementById('pension').value;
    var pension_empleador = document.getElementById('pension_empleador').value;
    var caja_compensacion = document.getElementById('caja_compensacion').value;
    var arl = document.getElementById('arl').value;
    var dotacion = document.getElementById('dotacion').value;
    var total_porcentaje = document.getElementById('total_porcentaje').value;
    var costo_mensual = document.getElementById('costo_mensual').value;
    var costo_hora = document.getElementById('costo_hora').value;

    // Crear un objeto con los datos a enviar al servidor
    var datos = {
        año: año,
        horas_mensuales: horas_mensuales,
        horas_semanales: horas_semanales,
        descripcion: descripcion,
        salario_minimo: salario_minimo,
        auxilio_tte: auxilio_tte,
        cesantias: cesantias,
        int_cesantias: int_cesantias,
        prima: prima,
        vacaciones: vacaciones,
        salud_empleado: salud_empleado,
        salud_empleador: salud_empleador,
        pension_empleado: pension_empleado,
        pension_empleador: pension_empleador,
        caja_compensacion: caja_compensacion,
        arl: arl,
        dotacion: dotacion,
        total_porcentaje: total_porcentaje,
        costo_mensual: costo_mensual,
        costo_hora: costo_hora
    };

 // Enviar los datos al servidor mediante una solicitud AJAX
 fetch('guardarsalarios.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify(datos),
})
.then(response => response.json())
.then(data => {
    // Verificar si la respuesta indica éxito o error
    if (data.success) {
        alert(data.message); // Mostrar mensaje de éxito
    } else {
        alert(data.message); // Mostrar mensaje de error
    }
})
.catch(error => {
    console.error('Error al guardar los datos:', error);
    // Mostrar un mensaje de error si falla la solicitud AJAX
    alert('Hubo un problema al guardar los datos. Por favor, inténtalo de nuevo.');
});
}
