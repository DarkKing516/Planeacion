// Contador para el código
var contadorCodigo = 1;

// Función para generar el siguiente código
function generarSiguienteCodigo() {
    // Formatear el contador como un número de dos dígitos rellenando con ceros a la izquierda
    var codigoGenerado = ("0" + contadorCodigo).slice(-2);
    
    contadorCodigo++;
    return codigoGenerado;
}

// Función para actualizar el placeholder del código con el siguiente código generado
function actualizarPlaceholderCodigo() {
    var codigoGenerado = generarSiguienteCodigo();
    document.getElementById("codigo").placeholder = "CÓDIGO GENERADO: " + codigoGenerado;
}

// Llamamos a la función al cargar la página
window.onload = function() {
    actualizarPlaceholderCodigo();
};

// Función para guardar el grupo
function guardarGrupo() {
    var codigoGenerado = generarSiguienteCodigo(); // Obtenemos el siguiente código generado
    var nombre = document.getElementById("nombre").value;
    if (!nombre.trim()) { // Verifica si el campo del nombre está vacío
        alert("POR FAVOR, INGRESE EL NOMBRE DEL GRUPO.");
        
        return; // Detiene la ejecución de la función si el campo del nombre está vacío
    }
    
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "guardarGrupo.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert('EL GRUPO ' + nombre + ' HA SIDO GUARDADO CORRECTAMENTE.' );                  
            // Limpiar los campos después de mostrar la alerta
            document.getElementById("nombre").value = '';
            // Actualizar el placeholder del código para el siguiente código generado
            actualizarPlaceholderCodigo();
        }
    };
    xhr.send("codigo=" + codigoGenerado + "&nombre=" + nombre);
 }

 // Función para eliminar un grupo
function eliminarGrupo(codigoGrupo) {
    // Confirmar si el usuario realmente quiere eliminar el grupo
    var confirmacion = confirm("¿ESTÁS SEGURO QUE QUIERES ELIMINAR ESTE GRUPO??");
    
    // Si el usuario confirma, proceder con la eliminación
    if (confirmacion) {
        // Realizar una solicitud HTTP POST al archivo eliminarGrupo.php
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "eliminarGrupo.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Manejar la respuesta del servidor
                alert(xhr.responseText);
                
                // Recargar la página para mostrar la lista actualizada de grupos
                window.location.reload();
            }
        };
        xhr.send("id=" + codigoGrupo);
    }
}

// Función para abrir el modal y cargar el formulario de edición
function abrirModalEditar(codigoGrupo) {
    var modal = document.getElementById("modalEditar");
    modal.style.display = "block";

    // Realizar una solicitud AJAX para cargar el formulario de edición dentro del modal
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "editarGrupo.php?codigo=" + codigoGrupo, true);
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
