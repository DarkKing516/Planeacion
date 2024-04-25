function leerCSV() {
    var input = document.getElementById('csvFileInput');
    var file = input.files[0];

    if (file) {
        var reader = new FileReader();

        reader.onload = function(e) {
            var csv = e.target.result;
            var lineas = csv.split('\n');

            var tablaHTML = '<table>';
            for (var i = 0; i < lineas.length; i++) {
                var datos = lineas[i].split(';'); // Cambiado a punto y coma
                tablaHTML += i === 0 ? '<tr style="background-color: #244183; color: #FFFFFF;">' : '<tr>';
                for (var j = 0; j < datos.length; j++) {
                    tablaHTML += i === 0 ? '<th>' + datos[j] + '</th>' : '<td>' + datos[j] + '</td>';
                }
                tablaHTML += '</tr>';
            }
            tablaHTML += '</table>';

            document.getElementById('tablaDatos').innerHTML = tablaHTML;

            // Actualizar el nombre del archivo en el placeholder
            document.getElementById('fileNamePlaceholder').value = file.name;
        };

        reader.readAsText(file, 'UTF-8'); // Cambiado a UTF-8
    } else {
        alert('POR FAVOR, SELECCIONE EL ARCHIVO CSV.');
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
    xhr.open('POST', 'guardarArticulos.php');
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
