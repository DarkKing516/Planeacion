function updateCodigoGrupo() {
    var grupoSelect = document.getElementById("grupo");
    var codigoInput = document.getElementById("cod_grupo");

    // Obtener el valor seleccionado del grupo
    var selectedCodigo = grupoSelect.value;

    // Asignar el valor seleccionado al campo de entrada de texto
    codigoInput.value = selectedCodigo;
    updateCodigoEstandar();
}

function updateCodigoProceso() {
    var procesoSelect = document.getElementById("proceso");
    var codigoInput = document.getElementById("cod_proceso");

    // Obtener el valor seleccionado del proceso
    var selectedCodigo = procesoSelect.value;

    // Asignar el valor seleccionado al campo de entrada de texto
    codigoInput.value = selectedCodigo;
    updateCodigoEstandar();
}

function updateCodigoArticulo() {
    var articuloSelect = document.getElementById("articulo");
    var pesoInput = document.getElementById("peso");
    var codigoInput = document.getElementById("cod_articulo");

    // Obtener el valor seleccionado del artículo
    var selectedCodigo = articuloSelect.value;

    // Asignar el valor seleccionado al campo de entrada de texto
    codigoInput.value = selectedCodigo;

    // Llamar a obtenerPeso después de actualizar el código del artículo
    obtenerPeso(); 

    // Actualizar el valor del campo de peso
    pesoInput.value = "Cargando..."; // Mostrar un mensaje mientras se carga el peso
}

function obtenerPeso() {
    var articuloCodigo = document.getElementById("articulo").value;
    
    // Realizar solicitud AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "obtenerPesoArticulo.php?codigo=" + articuloCodigo, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Actualizar el valor del campo de peso
            document.getElementById("peso").value = xhr.responseText;
        }
    };
    xhr.send();
}

function updateCodigoEstandar() {
    var cod_grupo = document.getElementById("cod_grupo").value;
    var cod_proceso = document.getElementById("cod_proceso").value;
    var cod_articulo = document.getElementById("cod_articulo").value;

    var codigo_estandar = cod_grupo + cod_proceso + cod_articulo;

    document.getElementById("codigo_estandar").value = codigo_estandar;

    // Concatenar nombre del proceso y código del artículo para el nombre del estándar
    var nombre_proceso = document.getElementById("proceso").options[document.getElementById("proceso").selectedIndex].text;
    var nombre_estandar = nombre_proceso + "_" + cod_articulo;

    document.getElementById("nombre_estandar_editar").value = nombre_estandar;
}

function guardarEstandar() {
    // Obtener los datos del formulario
    var formData = new FormData(document.getElementById("form-editar"));

    // Realizar la solicitud AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "guardarestandar.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Manejar la respuesta
            if (xhr.responseText == "success") {
                // Mostrar alerta de éxito
                alert("DATOS GUARDADOS CORRECTAMENTE.");
            } else {
                // Mostrar alerta de error
                alert("ERROR AL GUARDAR LOS DATOS: " + xhr.responseText);
            }
        }
    };
    xhr.send(formData);
}

function eliminarRegistro(codEst) {
    // Mostrar mensaje de confirmación
    var confirmacion = confirm("¿ESTÁS SEGURO QUE QUIERES ELIMINAR ESTE ESTÁNDAR?");

    // Si el usuario confirma
    if (confirmacion) {
        // Realizar la solicitud AJAX para eliminar el registro
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "eliminarEstandar.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Manejar la respuesta
                if (xhr.responseText == "success") {
                    // Mostrar alerta de éxito
                    alert("REGISTRO ELIMINADO CORRECTAMENTE.");
                    // Recargar la página para actualizar la tabla
                    location.reload();
                } else {
                    // Mostrar alerta de error
                    alert("ERROR AL ELIMINAR EL REGISTRO: " + xhr.responseText);
                }
            }
        };
        xhr.send("cod_estandar=" + codEst);
    }
}

function updateFileName(input) {
    var fileName = input.value.split(/(\\|\/)/g).pop(); // Obtener solo el nombre del archivo
    document.getElementById('fileNamePlaceholder').value = fileName;
}

function guardarDatos() {
    console.log("GUARDANDO DATOS..."); // Agregado para depurar
    var filas = document.querySelectorAll('#tablaDatos table tr');
    var datos = [];

    // Recorrer las filas de la tabla y recopilar los datos
    for (var i = 1; i < filas.length; i++) {
        var fila = filas[i];
        var celdas = fila.querySelectorAll('td');
        var datosFila = [];

        // Recorrer las celdas de la fila y obtener los datos
        for (var j = 0; j < celdas.length; j++) {
            datosFila.push(celdas[j].innerText.trim());
        }

        // Agregar los datos de la fila al arreglo de datos
        datos.push(datosFila);
    }

    // Enviar los datos al servidor utilizando AJAX
    console.log("ENVIANDO DATOS AL SERVIDOR..."); // Mensaje de depuración
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'guardarEstandar.php');
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log('RESPUESTA DEL SERVIDOR:', xhr.responseText); // Mensaje de depuración
            alert('DATOS GUARDADOS CORRECTAMENTE.');
        } else {
            console.error('ERROR EN LA SOLICITUD:', xhr.status); // Mensaje de depuración
            alert('ERROR AL GUARDAR LOS DATOS. POR FAVOR, INTÉNTALO DE NUEVO.');
        }
    };
    xhr.send(JSON.stringify(datos));
}

function confirmarActualizacion() {
    if (confirm("¿ESTÁS SEGURO QUE QUIERES EDITAR ESTE ESTÁNDAR?")) {
        // Si el usuario confirma, enviar el formulario para actualizar
        document.getElementById("form-editar").submit();
    } else {
        // Cancelar la acción
        return false;
    }
}

function editarRegistro(codEst) {
    // Obtener los datos del registro a editar
    var nombre_estandar = prompt("Nuevo NOM. ESTÁNDAR:");
    var t_estandar = prompt("Nuevo T. ESTANDAR (MIN):");

    // Si se proporciona un nombre y un tiempo estándar
    if (nombre_estandar !== null && t_estandar !== null) {
        // Realizar la solicitud AJAX para actualizar el registro
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "editarEstandar.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Manejar la respuesta
                if (xhr.responseText == "success") {
                    // Mostrar alerta de éxito
                    alert("REGISTRO ACTUALIZADO CORRECTAMENTE.");
                    // Recargar la página para actualizar la tabla
                    location.reload();
                } else {
                    // Mostrar alerta de error
                    alert("ERROR AL ACTUALIZAR EL REGISTRO: " + xhr.responseText);
                }
            }
        };
        // Enviar los datos del formulario al servidor
        xhr.send("cod_estandar=" + codEst + "&nombre_estandar=" + nombre_estandar + "&t_estandar=" + t_estandar);
    }
}
