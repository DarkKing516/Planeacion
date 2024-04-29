document.addEventListener("DOMContentLoaded", function () {
    // Función para enviar la solicitud AJAX al servidor y actualizar la tabla de producción
    function filtrarProduccion() {
        // Obtener los valores de los selectores (si los tienes)
        // Puedes usar document.getElementById() o cualquier otra forma de obtener los valores de los selectores
        
        // Construir la URL de la solicitud AJAX (puedes enviar los parámetros como query string)
        var url = "filtrarProduccion.php";
        
        // Realizar la solicitud AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("GET", url, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Manejar la respuesta del servidor
                var tablaProduccion = document.getElementById("tabla-produccion-container");
                tablaProduccion.innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    // Función para enviar la solicitud AJAX al servidor y descargar el archivo de producción
    function descargarProduccion() {
        // Construir la URL de la solicitud AJAX
        var url = "descargarProduccion.php";
        
        // Realizar la solicitud AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("GET", url, true);
        xhr.responseType = "blob"; // Indicar que se espera una respuesta de tipo blob
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Crear un objeto URL para el blob recibido
                var blobURL = URL.createObjectURL(xhr.response);
                
                // Crear un enlace temporal para descargar el archivo
                var a = document.createElement("a");
                a.href = blobURL;
                a.download = "INFORME PRODUCCIÓN.xls"; // Nombre del archivo
                document.body.appendChild(a);
                a.click();
                
                // Limpiar el enlace y liberar el objeto URL
                URL.revokeObjectURL(blobURL);
                document.body.removeChild(a);
            }
        };
        xhr.send();
    }

    // Llamar a la función para filtrar la producción cuando se haga clic en el botón de filtrar
    var filtroButton = document.getElementById("filtro-button");
    filtroButton.addEventListener("click", function () {
        filtrarProduccion();
    });

    // Llamar a la función para descargar la producción cuando se haga clic en el botón de descarga
    var descargaButton = document.getElementById("descarga-button");
    descargaButton.addEventListener("click", function () {
        descargarProduccion();
    });
});
