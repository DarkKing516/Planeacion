document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("filtro-button").addEventListener("click", function() {
        var estado = document.getElementById("estado").value;
        var cargo = document.getElementById("cargo").value;
        var sede = document.getElementById("sede").value;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("tabla-empleados-container").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "filtrarEmpleados.php?estado=" + estado + "&cargo=" + cargo + "&sede=" + sede, true);
        xhttp.send();
    });

    document.getElementById("descarga-button").addEventListener("click", function() {
        // Mostrar un mensaje de confirmación
        var confirmacion = confirm("¿ESTÁS SEGURO QUE QUIERES DESCARGAR ESTE INFORME DE EMPLEADOS?");
        
        // Si el usuario confirma la descarga
        if (confirmacion) {
            // Obtener los valores de estado, cargo y sede
            var estado = document.getElementById("estado").value;
            var cargo = document.getElementById("cargo").value;
            var sede = document.getElementById("sede").value;

            // Construir la URL para la descarga CSV
            var csvDownloadUrl = "descargarEmpl.php?estado=" + estado + "&cargo=" + cargo + "&sede=" + sede;

            // Redirigir al usuario para descargar el archivo CSV
            window.location.href = csvDownloadUrl;
            
            // Mostrar un mensaje de éxito
            alert("ARCHIVO DESCARGADO CORRECTAMENTE.");
        } else {
            // Mostrar un mensaje de cancelación si el usuario no confirma la descarga
            alert("DESCARGA CANSELADA.");
        }
    });
});
