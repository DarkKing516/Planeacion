$(document).ready(function() {
    // Función para cargar los empleados y tiempos de descanso según la sede seleccionada
    $('#sede').change(function() {
        var sede = $(this).val();
        $.ajax({
            type: 'POST',
            url: 'obtenerEmpl&Descan.php',
            data: { sede: sede },
            dataType: 'json',
            success: function(data) {
                $('#empleados').empty(); // Limpiar opciones anteriores
                $.each(data.empleados, function(index, empleado) {
                    $('#empleados').append('<option value="' + empleado.cedula + '">' + empleado.nombre + '</option>');
                });
                $('#empleados').trigger('change');

                // Actualizar tiempos de descanso
                $('#descanso1_input').val(data.tiempos['DESCANSO1']);
                $('#descanso2_input').val(data.tiempos['DESCANSO2']);
                $('#almuerzo_input').val(data.tiempos['ALMUERZO']);
                calcularTiempoReal(); // Llamar a la función para calcular tiempo real
            }
        });
    });

    // Función para actualizar el campo cedula con el valor seleccionado de empleados
    $('#empleados').change(function() {
        var cedula = $(this).val();
        $('#cedula').val(cedula);
    });

    // Función para cargar los grupos desde PHP
    $.getJSON('obtener_grupos.php', function(data) {
        $('#grupo').empty(); // Limpiar opciones anteriores
        // Agregar opción inicial deshabilitada con el texto del placeholder
        $('#grupo').append('<option value="" disabled selected>SELECCIONE UN GRUPO</option>');
        $.each(data, function(key, value) {
            $('#grupo').append('<option value="' + value.cod_grupo + '">' + value.grupo + '</option>');
        });
    });

    $('#grupo').change(function() {
        var codigoGrupo = $(this).val();
        $('#cod_grupo').val(codigoGrupo);
        obtenerArticulos(codigoGrupo); // Llamar a la función para obtener los artículos
    });
    
    // Función para obtener los artículos según el grupo seleccionado
    function obtenerArticulos(codigoGrupo) {
        $.ajax({
            type: 'POST',
            url: 'obtener_articulos.php',
            data: { codigoGrupo: codigoGrupo },
            dataType: 'json',
            success: function(data) {
                $('#articulo').empty(); // Limpiar opciones anteriores
                // Agregar opción inicial deshabilitada con el texto del placeholder
                $('#articulo').append('<option value="" disabled selected>SELECCIONE UN ARTÍCULO</option>');
                $.each(data, function(index, articulo) {
                    $('#articulo').append('<option value="' + articulo.cod_articulo + '">' + articulo.articulo + '</option>');
                });
            }
        });
    }
    
    $(document).ready(function() {
        // Evento change para cargar los artículos según el grupo seleccionado
        $('#grupo').change(function() {
            var codigoGrupo = $(this).val();
            $('#cod_grupo').val(codigoGrupo);
            obtenerArticulos(codigoGrupo);
        });
    
        // Evento change para actualizar el tiempo estándar al seleccionar un nombre estándar
        $('#nombre_estandar').change(function() {
            var nombre_estandar = $(this).val();
            obtenerTiempoEstandar(nombre_estandar);
        });
    });
    
    // Función para obtener los artículos según el grupo seleccionado
    function obtenerArticulos(codigoGrupo) {
        $.ajax({
            type: 'POST',
            url: 'obtener_articulos.php',
            data: { codigoGrupo: codigoGrupo },
            dataType: 'json',
            success: function(data) {
                $('#articulo').empty();
                $('#articulo').append('<option value="" disabled selected>SELECCIONE UN ARTÍCULO</option>');
                $.each(data, function(index, articulo) {
                    $('#articulo').append('<option value="' + articulo.cod_articulo + '">' + articulo.articulo + '</option>');
                });
            }
        });
    }
    
    // Función para obtener el tiempo estándar según el nombre estándar seleccionado
    function obtenerTiempoEstandar(nombre_estandar) {
        var cod_articulo = $('#cod_articulo').val();
        $.ajax({
            type: 'POST',
            url: 'obtener_t_estandar.php',
            data: { cod_articulo: cod_articulo, nombre_estandar: nombre_estandar },
            dataType: 'json',
            success: function(data) {
                $('#t_estandar').val(data);
            }
        });
    }
    
    // Función para evitar la activación del checkbox al hacer clic fuera del mismo
    $('input[type="checkbox"]').click(function(event) {
        var rect = this.getBoundingClientRect();
        var dentroDelCheckbox = (event.clientX >= rect.left && event.clientX <= rect.right && event.clientY >= rect.top && event.clientY <= rect.bottom);
        if (!dentroDelCheckbox) {
            event.preventDefault();
        }
    });

    // Función para cargar los tiempos de descanso según la sede seleccionada
    $('#sede2').change(function() {
        var sedeSeleccionada = $(this).val();
        obtenerTiempoDescansos(sedeSeleccionada);
    });

    // Función para calcular el tiempo real
    $('#descanso1_checkbox, #descanso2_checkbox, #almuerzo_checkbox').change(function() {
        calcularTiempoReal();
    });

    // Función para obtener los tiempos de descanso
    function obtenerTiempoDescansos(sede) {
        $.ajax({
            type: 'POST',
            url: 'obtenerTiempoDescansos.php',
            data: { sede: sede },
            success: function(response) {
                if (response !== "Error") {
                    var tiempos = JSON.parse(response);
                    $('#descanso1_input').val(tiempos['DESCANSO1']);
                    $('#descanso2_input').val(tiempos['DESCANSO2']);
                    $('#almuerzo_input').val(tiempos['ALMUERZO']);
                    calcularTiempoReal();
                } else {
                    alert("Error: No se encontraron datos para la sede seleccionada");
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("Error al obtener los tiempos de descanso");
            }
        });
    }

    // Función para calcular el tiempo real
    function calcularTiempoReal() {
        var tiempoLaborado = parseInt($('#tiempoLaborado').val()) || 0;
        var tiempoDescanso1 = $('#descanso1_checkbox').is(':checked') ? parseInt($('#descanso1_input').val()) || 0 : 0;
        var tiempoDescanso2 = $('#descanso2_checkbox').is(':checked') ? parseInt($('#descanso2_input').val()) || 0 : 0;
        var tiempoAlmuerzo = $('#almuerzo_checkbox').is(':checked') ? parseInt($('#almuerzo_input').val()) || 0 : 0;

        var descanso1_checked = $('#descanso1_checkbox').is(':checked');
        var descanso2_checked = $('#descanso2_checkbox').is(':checked');
        var almuerzo_checked = $('#almuerzo_checkbox').is(':checked');

        $('#descanso1_hidden').val(descanso1_checked ? 'SI' : 'NO');
        $('#descanso2_hidden').val(descanso2_checked ? 'SI' : 'NO');
        $('#almuerzo_hidden').val(almuerzo_checked ? 'SI' : 'NO');

        var totalDescanso = tiempoDescanso1 + tiempoDescanso2 + tiempoAlmuerzo;
        $('#totalDescanso').val(totalDescanso);

        var tiempoReal = tiempoLaborado - totalDescanso;

        // Si el tiempo real es mayor a 510 minutos, calcular las horas extras
        var horasExtra = tiempoReal > 510 ? tiempoReal - 510 : 0;

        $('#tiempoReal').val(tiempoReal);
        $('#horasExtra').val(horasExtra);
    }

    // Función para calcular el tiempo laborado y el tiempo real
    function calcularTiempos() {
        var horaInicio = $('#hora_inicio').val();
        var horaFin = $('#hora_fin').val();
        if (horaInicio && horaFin) {
            var fechaInicio = new Date('2000-01-01T' + horaInicio);
            var fechaFin = new Date('2000-01-01T' + horaFin);
            var diferenciaMs = fechaFin - fechaInicio;
            var diferenciaMin = Math.round(diferenciaMs / 60000);
            var totalDescanso = parseInt($('#totalDescanso').val()) || 0;
            var tiempoLaborado = diferenciaMin - totalDescanso;
            $('#tiempoLaborado').val(tiempoLaborado);
            calcularTiempoReal(); // Llamar a la función para actualizar el tiempo real y las horas extras
        }
    }

    // Evento de cambio para los checkboxes de descanso y almuerzo
    $('#descanso1_checkbox, #descanso2_checkbox, #almuerzo_checkbox').change(function() {
        calcularTiempoReal();
    });

    // Evento de cambio para los campos de hora de inicio y hora de fin
    $('#hora_inicio, #hora_fin').change(function() {
        calcularTiempos();
    });

    // Evento de cambio para el campo de total de descanso
    $('#totalDescanso').change(function() {
        calcularTiempos();
    });
});

$(document).ready(function() {
    $('#boton-mas').click(function() {
        // Clonar el conjunto de elementos existentes
        var nuevoConjunto = $('#conjunto-existente').clone();
        
        // Limpiar los valores de los nuevos elementos si es necesario
        nuevoConjunto.find('input, select').val('');
        
        // Agregar el nuevo conjunto al final del formulario
        $('#formulario').append(nuevoConjunto);

        // Agregar el botón de cancelar al nuevo conjunto
        nuevoConjunto.append('<button type="button" class="boton-cancelar">-</button>');
    });
   
    // Evento de clic para el botón "Cancelar"
    $(document).on('click', '.boton-cancelar', function() {
        // Eliminar el conjunto de elementos correspondiente al botón "Cancelar" presionado
        $(this).closest('.conjunto').remove();
    });
});

function actualizarCodArticulo() {
    var input = document.getElementById("articulo");
    var datalistOptions = document.getElementById("lista_articulos").getElementsByTagName("option");

    // Si se ha seleccionado una opción del datalist, actualiza el campo cod_articulo
    for (var i = 0; i < datalistOptions.length; i++) {
        if (datalistOptions[i].value === input.value) {
            document.getElementById("cod_articulo").value = datalistOptions[i].getAttribute("data-cod");
            return;
        }
    }
    // Si no se encuentra ninguna coincidencia en el datalist, limpia el campo cod_articulo
    document.getElementById("cod_articulo").value = "";
}

// Función para obtener los nombres estándar relacionados con el artículo seleccionado
function obtenerNombresEstandar() {
    var cod_articulo = $('#cod_articulo').val();
    $.ajax({
        type: 'POST',
        url: 'obtener_estandar.php', // Ruta a tu archivo PHP que maneja esta consulta
        data: { cod_articulo: cod_articulo },
        dataType: 'json',
        success: function(data) {
            $('#nombre_estandar').empty(); // Limpiar opciones anteriores
            $('#nombre_estandar').append('<option value="" disabled selected>SELECCIONE UN PROCESO</option>');
            $.each(data, function(index, estandar) {
                // Usar la clave 'nombre_estandar' para mostrar el nombre estándar
                $('#nombre_estandar').append('<option value="' + estandar.nombre_estandar + '">' + estandar.nombre_estandar + '</option>');
            });
        }
    });
}
