// Función para leer el contenido del archivo CSV
function leerCSV() {
    // Obtener el elemento de entrada de archivo CSV
    var input = document.getElementById('csvFileInput');
    
    // Verificar si se seleccionó un archivo
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        // Leer el contenido del archivo CSV
        reader.onload = function (e) {
            // Mostrar el contenido del archivo en la tabla de datos
            mostrarTablaDatos(e.target.result);
        };

        // Leer el archivo como texto
        reader.readAsText(input.files[0]);
    } else {
        alert('Por favor seleccione un archivo CSV.');
    }
}

// Función para mostrar el contenido del archivo CSV en una tabla
function mostrarTablaDatos(csvData) {
    // Dividir el contenido en líneas
    var lines = csvData.split("\n");
    
    // Crear una tabla HTML para mostrar los datos
    var tableHTML = '<table border="1">';
    
    // Recorrer las líneas del archivo CSV
    lines.forEach(function(line) {
        // Dividir la línea en datos separados por punto y coma (;)
        var data = line.split(";");
        
        // Crear una fila de tabla para cada línea de datos
        tableHTML += '<tr>';
        data.forEach(function(cell) {
            tableHTML += '<td>' + cell + '</td>';
        });
        tableHTML += '</tr>';
    });
    
    // Cerrar la tabla
    tableHTML += '</table>';
    
    // Mostrar la tabla en el div con id "tablaDatos"
    document.getElementById('tablaDatos').innerHTML = tableHTML;
}

// Función para guardar los datos en la base de datos mediante AJAX
function guardarDatos() {
    // Obtener los datos de la tabla
    var table = document.getElementById('tablaDatos');
    var rows = table.getElementsByTagName('tr');
    var data = [];

    // Recorrer las filas de la tabla
    for (var i = 1; i < rows.length; i++) { // Empezar desde 1 para omitir la fila de encabezados
        var cells = rows[i].getElementsByTagName('td');
        var rowData = [];
        
        // Recorrer las celdas de cada fila
        for (var j = 0; j < cells.length; j++) {
            rowData.push(cells[j].innerText);
        }
        
        // Agregar los datos de la fila al array de datos
        data.push(rowData);
    }

    // Enviar los datos al servidor mediante AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "guardarEstandares.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert(xhr.responseText); // Mostrar la respuesta del servidor
        }
    };
    xhr.send(JSON.stringify(data));
}

// Función para actualizar el nombre del archivo seleccionado
function updateFileName(input) {
    var fileName = input.files[0].name;
    var label = document.querySelector('label[for="csvFileInput"]');
    label.innerHTML = 'Archivo seleccionado: ' + fileName;
}
