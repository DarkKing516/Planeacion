// Función para guardar el descanso
function guardarDescanso() {
    var nombre = document.getElementById("nom_descanso").value;
    var sede = document.getElementById("sede").value;
    var inicio = document.getElementById("ini_descanso").value;
    var fin = document.getElementById("fin_descanso").value;
    var tiempo = document.getElementById("tiempo_des").value;
    if (!nombre.trim()) { // Verifica si el campo del nombre está vacío
        alert("POR FAVOR, INGRESE EL NOMBRE DEL DESCANSO.");
        return false; // Detiene la ejecución de la función si el campo del nombre está vacío
    }
    
    var codigoGenerado = generarSiguienteCodigo(); // Obtiene el siguiente código generado
    
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "guardarDescanso.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // Mostrar alerta con el mensaje de respuesta del servidor
                if (xhr.responseText.trim() === 'success') {
                    // Limpiar los campos después de mostrar la alerta
                    document.getElementById("nom_descanso").value = '';
                    document.getElementById("tiempo_des").value = '';
                    // Actualizar el placeholder del código para el siguiente código generado
                    actualizarPlaceholderCodigo();
                    
                    // Mostrar el alerta de éxito
                    alert("DESCANSO GUARDADO CORRECTAMENTE.");
                } else {
                    // Mostrar alerta si ocurrió algún error al guardar
                    alert('ERROR AL GUARDAR EL DESCANSO.');
                }
            } else {
                // Mostrar alerta si ocurrió algún problema con la solicitud AJAX
                alert('ERROR: No se pudo conectar con el servidor.');
            }
        }
    };
    xhr.send("cod_descanso=" + codigoGenerado + "&nom_descanso=" + nombre + "&sede=" + sede + "&ini_descanso=" + inicio + "&fin_descanso=" + fin + "&tiempo_des=" + tiempo);
    
    return false; // Evitar que el formulario se envíe de manera tradicional
}

// Función para abrir el modal y cargar el formulario de edición
function abrirModalEditar(codigoDescanso) {
    var modal = document.getElementById("modalEditar");
    modal.style.display = "block";

    // Realizar una solicitud AJAX para cargar el formulario de edición dentro del modal
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "editarDescanso.php?id=" + codigoDescanso, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("formularioEditar").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

// Función para cerrar el modal
function cerrarModalEditar() {
    var modal = document.getElementById("modalEditar");
    modal.style.display = "none";
}

// Función para calcular la diferencia de tiempo
function calcularTiempo() {
    var horaInicio = document.getElementById('ini_descanso').value;
    var horaFin = document.getElementById('fin_descanso').value;

    // Validar que se ingresen ambas horas
    if (horaInicio && horaFin) {
        // Convertir las horas a objetos Date
        var inicio = new Date('2000-01-01T' + horaInicio + ':00');
        var fin = new Date('2000-01-01T' + horaFin + ':00');

        // Calcular la diferencia de tiempo en milisegundos
        var diferencia = fin - inicio;

        // Convertir la diferencia a horas, minutos y segundos
        var horas = Math.floor(diferencia / 3600000);
        diferencia %= 3600000;
        var minutos = Math.floor(diferencia / 60000);
        diferencia %= 60000;
        var segundos = Math.floor(diferencia / 1000);

        // Mostrar el resultado en el placeholder
        document.getElementById('tiempo_des').value = horas + ':' + minutos + ':' + segundos;
    } else {
        document.getElementById('tiempo_des').value = ''; // Limpiar el campo si falta alguna hora
    }
}

// Llamamos a la función al cargar la página
window.onload = function() {
    actualizarPlaceholderCodigo();
};

// Función para eliminar un descanso
function eliminarDescanso(codigoDescanso) {
    // Confirmar si el usuario realmente quiere eliminar el descanso
    var confirmacion = confirm("¿ESTÁS SEGURO QUE QUIERES ELIMINAR ESTE DESCANSO?");
    
    // Si el usuario confirma, proceder con la eliminación
    if (confirmacion) {
        // Realizar una solicitud HTTP POST al archivo eliminarDescanso.php
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "eliminarDescanso.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Manejar la respuesta del servidor
                alert(xhr.responseText);
                
                // Recargar la página para mostrar la lista actualizada de descansos
                window.location.reload();
            }
        };
        xhr.send("id=" + codigoDescanso);
    }
}
