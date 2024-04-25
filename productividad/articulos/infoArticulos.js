document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("filtro-button").addEventListener("click", function() {
        var tipo = document.getElementById("tipo").value;
        var grupo = document.getElementById("grupo").value;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("tabla-articulos-container").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "filtrarArticulos.php?tipo=" + tipo + "&grupo=" + grupo, true);
        xhttp.send();
    });

    document.getElementById("descarga-button").addEventListener("click", function() {
        // Obtener los valores de tipo y grupo
        var tipo = document.getElementById("tipo").value;
        var grupo = document.getElementById("grupo").value;

        // Alerta para confirmar la descarga
        var confirmacion = confirm("¿ESTÁS SEGURO QUE QUIERES DESCARGAR ESTE INFORME DE ARTÍCULOS?");
        
        // Si el usuario confirma la descarga
        if (confirmacion) {
            // Construir la URL para la descarga CSV
            var csvDownloadUrl = "descargarArt.php?tipo=" + tipo + "&grupo=" + grupo;

            // Redirigir al usuario para descargar el archivo CSV
            window.location.href = csvDownloadUrl;
            
            // Mostrar mensaje de descarga exitosa
            alert("ARCHIVO DESCARGADO CORRECTAMENTE.");
        } else {
            // Mostrar mensaje de cancelación
            alert("DESCARGA CANCELADA.");
        }
    });
});
