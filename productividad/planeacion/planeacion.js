$(document).ready(function() {
    // Cargar empleados de la sede inicial seleccionada
    var sedeSeleccionada = $("#sede").val();
    cargarEmpleados(sedeSeleccionada);

    // Actualizar empleados cuando se cambia la sede seleccionada
    $("#sede").change(function() {
        var nuevaSede = $(this).val();
        cargarEmpleados(nuevaSede);
    });

    function cargarEmpleados(sede) {
        $.ajax({
            url: "obtener_empleados.php",
            type: "POST",
            data: { sede: sede },
            success: function(response) {
                $("#tabla-empleados").html(response);
                // Actualizar el número de empleados autorizados
                $("#per_autorizado").val(response.per_autorizado);
                // Agregar opciones de grupo y cargar artículo en cada fila
                agregarOpcionesEstandar();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function agregarOpcionesEstandar() {
        // Realizar una petición AJAX para obtener los datos de grupo desde la tabla estandar
        $.ajax({
            url: "obtener_grupos_estandar.php",
            type: "GET",
            dataType: 'json', // Especificamos que esperamos datos en formato JSON
            success: function(grupos) {
                // Recorrer cada fila excepto la primera (encabezados de columna)
                $("#tabla-empleados tr:not(:first)").each(function(index, fila) {
                    // Crear el menú desplegable con opciones de grupo
                    var opcionesGrupo = '<select class="grupo">';
                    opcionesGrupo += '<option value="">SELECCIONAR GRUPO</option>';
                    // Agregar las opciones de grupo obtenidas de la tabla estandar
                    $.each(grupos, function(index, grupo) {
                        opcionesGrupo += '<option value="' + grupo + '">' + grupo + '</option>';
                    });
                    opcionesGrupo += '</select>';

                    // Agregar el menú desplegable de grupo a la columna 3 de la fila
                    $(fila).find("td:eq(3)").html(opcionesGrupo);

                    // Crear el menú desplegable con opciones de artículo
                    var opcionesArticulo = '<select class="articulo">';
                    opcionesArticulo += '<option value="">SELECCIONAR ARTÍCULO</option>';
                    opcionesArticulo += '</select>';

                    // Agregar el menú desplegable de artículo a la columna 5 de la fila
                    $(fila).find("td:eq(5)").html(opcionesArticulo);

                    // Manejar evento de cambio en el menú desplegable de grupo
                    $(fila).find(".grupo").change(function() {
                        var grupoSeleccionado = $(this).val();
                        cargarArticulo(grupoSeleccionado, fila);
                    });

                    // Manejar evento de cambio en el menú desplegable de artículo
                    $(fila).find(".articulo").change(function() {
                        var articuloSeleccionado = $(this).val();
                        cargarPesoArticulo(articuloSeleccionado, fila);
                    });
                });

                // Mostrar el código del artículo en la columna 4 (fuera del bucle each)
                $("#tabla-empleados tr:not(:first) td:eq(4)").text(function(index) {
                    return $(this).closest("tr").find("td:eq(5) option:selected").text();
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    // Función para cargar los artículos según el grupo seleccionado
    function cargarArticulo(grupoSeleccionado, fila) {
        $.ajax({
            url: "obtener_articulos_por_grupos.php",
            type: "GET",
            data: { grupo: grupoSeleccionado },
            dataType: 'json',
            success: function(articulos) {
                // Limpiar el select de artículos
                var selectArticulo = $(fila).find(".articulo");
                selectArticulo.empty();

                // Agregar opción por defecto
                selectArticulo.append('<option value="">SELECCIONAR ARTÍCULO</option>');
                // Crear un array para almacenar los códigos de artículo
                var codigosArticulo = [];

                // Agregar las opciones de artículos
                $.each(articulos, function(index, articulo) {
                    // Agregar opción al select de artículos con el nombre del artículo
                    selectArticulo.append('<option value="' + articulo.nombre + '">' + articulo.nombre + '</option>');

                    // Almacenar el código del artículo en un atributo de datos en el elemento de fila
                    $(fila).data("codigoArticulo", articulo.codigo);
                });

                // Llamar a la función para cargar los estándares cuando se cambie la selección del artículo
                selectArticulo.change(function() {
                    var articuloSeleccionado = $(this).val();
                    cargarEstandares(articuloSeleccionado, fila);

                    // Mostrar el código del artículo seleccionado en la columna 4
                    var codigoArticulo = $(fila).data("codigoArticulo");
                    $(fila).find("td:eq(4)").text(codigoArticulo);
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    // Función para cargar los estándares según el artículo seleccionado
    function cargarEstandares(articuloSeleccionado, fila) {
        $.ajax({
            url: "estandar_por_articulo.php",
            type: "GET",
            data: { articulo: articuloSeleccionado },
            dataType: 'json',
            success: function(estandares) {
                // Limpiar el select de nombres estándar
                var selectNombreEstandar = $('<select class="estandar"></select>');

                // Agregar opción por defecto
                selectNombreEstandar.append('<option value="">SELECCIONAR ESTÁNDAR</option>');

                // Agregar las opciones de nombres estándar
                $.each(estandares, function(index, estandar) {
                    // Agregar opción al select de nombres estándar con el nombre del estándar
                    selectNombreEstandar.append('<option value="' + estandar.nombre_estandar + '">' + estandar.nombre_estandar + '</option>');
                });

                // Agregar el menú desplegable de estándar a la columna 6 de la fila
                $(fila).find("td:eq(6)").html(selectNombreEstandar);

                // Manejar evento de cambio en el menú desplegable de estándar
                selectNombreEstandar.change(function() {
                    var nombreEstandarSeleccionado = $(this).val();
                    // Buscar el t_estandar correspondiente al nombre_estandar seleccionado
                    var tEstandar = estandares.find(function(estandar) {
                        return estandar.nombre_estandar === nombreEstandarSeleccionado;
                    }).t_estandar;
                    // Mostrar el t_estandar en la columna 7
                    $(fila).find("td:eq(7)").text(tEstandar);
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    // Función para cargar el peso del artículo
    function cargarPesoArticulo(articuloSeleccionado, fila) {
        $.ajax({
            url: "peso_articulo.php",
            type: "GET",
            data: { articulo: articuloSeleccionado }, // Cambiado a 'articulo' en lugar de 'codigo'
            dataType: 'json',
            success: function(response) {
                // Verificar si la respuesta contiene un error
                if (response.hasOwnProperty('error')) {
                    console.error(response.error);
                } else {
                    // Mostrar el peso del artículo en la columna 8
                    $(fila).find("td:eq(8)").text(response.peso);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    // Delegar el evento oninput para calcular los minutos
    $("#tabla-empleados").on("input", ".horas_dia", function() {
        var index = $(this).data("index");
        calcularMinutos(index);
    });

    function calcularMinutos(index) {
        console.log("INDEX DE LA FILA:", index);

        // Obtener el valor de horas_dia para la fila específica y parsearlo como un número
        var horasDia = parseFloat($("#horas_dia_" + index).val());
        
        // Calcular los minutos
        var minutosDia = horasDia * 60;
        
        // Actualizar el valor de minutos_dia para la fila específica
        $("#minutos_dia_" + index).val(minutosDia);

        // Obtener el valor de t_estandar para la fila específica y parsearlo como un número
        var tEstandar = parseFloat($("#t_estandar_" + index).text());

        // Verificar si tEstandar es un número válido
        if (!isNaN(tEstandar) && tEstandar !== 0) {
            // Calcular tarea_dia dividiendo minutosDia entre tEstandar
            var tareaDia = minutosDia / tEstandar;

            // Actualizar el valor de tarea_dia para la fila específica
            $("#tarea_dia_" + index).val(tareaDia.toFixed(2)); // Redondear a dos decimales y establecer el valor en el campo tarea_dia
        } else {
            // Manejar el caso donde tEstandar no es un número válido
            $("#tarea_dia_" + index).val("VALOR DE t_estandar INVÁLIDO");
        }
        // Obtener el valor del peso del artículo para la fila específica y parsearlo como un número
        var pesoArticulo = parseFloat($("#peso_articulo_" + index).text());

        // Calcular el peso total multiplicando el peso del artículo por los minutos del día
        var pesoTotal = pesoArticulo * tareaDia;

        // Actualizar el valor de peso_total para la fila específica
        $("#peso_total_" + index).val(pesoTotal.toFixed(2)); // Redondear a dos decimales y establecer el valor en el campo peso_total

        // Verificar si los minutos calculados son menores a 510
        if (minutosDia < 510) {
            var minutosFaltantes = 510 - minutosDia;
            alert("AL EMPLEADO LE FALTAN " + minutosFaltantes + " MINUTOS PARA COMPLETAR SU JORNADA.");
        }
    }

    // Manejar el clic en el botón de guardar
    $('#guardarDatos').click(function(){
        // Aquí puedes agregar el código para guardar los datos, por ejemplo:
        $.ajax({
            url: 'procesar_formulario.php',
            method: 'POST',
            data: $('#form-estandar').serialize(), // Envía los datos del formulario
            success: function(response) {
                alert('DATOS GUARDADOS CORRECTAMENTE');
                // Puedes realizar alguna acción adicional después de guardar los datos si es necesario
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('OCURRIÓ UN ERROR AL GUARDAR LOS DATOS');
            }
        });
    });
});
