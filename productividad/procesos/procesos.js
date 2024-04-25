// Función para generar el siguiente código en orden secuencial
function generateNextCode(lastCode) {
    if (!lastCode) {
        return "01";
    } else {
        // Incrementar el último código en uno
        var nextCode = parseInt(lastCode) + 1;
        // Formatear el nuevo código para que tenga siempre dos dígitos
        return ("0" + nextCode).slice(-2);
    }
}

// Cuando la página se carga
window.onload = function() {
    // Usar el último código generado para llenar el campo de código en el formulario
    document.querySelector('input[name="codigo"]').value = generateNextCode(lastCode);
    
    // Usar las opciones de grupo para llenar el select en el formulario
    var select = document.querySelector('select[name="grupo"]');
    for (var i = 0; i < gruposOptions.length; i++) {
        var option = document.createElement('option');
        option.text = gruposOptions[i];
        option.value = gruposOptions[i];
        select.add(option);
    }
};

function mostrarEditarModal(id, codigo, nombre, grupo) {
    var modal = document.getElementById("editarProcesoModal");
    var editId = document.getElementById("editId");
    var editCodigo = document.getElementById("editCodigo");
    var editNombre = document.getElementById("editNombre");
    var editGrupo = document.getElementById("editGrupo");

    // Asignar valores a los campos del formulario dentro del modal
    editId.value = id;
    editCodigo.value = codigo;
    editNombre.value = nombre;
    editGrupo.value = grupo;

    // Mostrar el modal
    modal.style.display = "block";
}

// JavaScript para cerrar el modal al hacer clic en la X
var closeBtn = document.getElementsByClassName("close")[0];
closeBtn.onclick = function() {
    var modal = document.getElementById("editarProcesoModal");
    modal.style.display = "none";
};

// Función para mostrar la confirmación antes de enviar el formulario de actualización
function confirmarEditarProceso() {
    if (confirm("¿ESTÁS SEGURO QUE QUIERES EDITAR ESTE PROCESO?")) {
        document.getElementById("editarProcesoForm").submit();
    }
}
