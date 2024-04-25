// Función para eliminar un salario
function eliminarSalario(id) {
    // Confirmar con el usuario antes de eliminar
    var confirmacion = confirm("¿Estás seguro que quieres eliminar este registro?");
    
    // Si el usuario confirma, procedemos con la eliminación
    if (confirmacion) {
        // Realizar una solicitud AJAX para eliminar el salario con el ID proporcionado
        fetch('eliminarSalario.php?id=' + id, {
            method: 'DELETE'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('ERROR AL ELIMINAR EL SALARIO.');
            }
            // Recargar la página después de eliminar el salario
            location.reload();
        })
        .catch(error => {
            console.error('ERROR AL ELIMINAR EL SALARIO:', error);
            // Mostrar mensaje de error al usuario si la eliminación falla
            alert('HUBO UN ERROR AL ELIMINAR EL SALARIO.');
        });
    }
}

// Función para editar un salario
function editarSalario(id) {
    // Mostrar el modal de edición
    var modalEditar = document.getElementById("modalEditar");
    modalEditar.style.display = "block";
    
    // Obtener los datos del salario para mostrar en los campos de edición
    fetch('obtenerSalario.php?id=' + id)
    .then(response => response.json())
    .then(data => {
        // Rellenar los campos de edición con los datos del salario
        document.getElementById("inputAño").value = data.año;
        document.getElementById("inputHorasMensuales").value = data.horas_mensuales;
        document.getElementById("inputHorasSemanales").value = data.horas_semanales;
        document.getElementById("inputDescripcion").value = data.descripcion;
        document.getElementById("inputSalarioMinimo").value = data.salario_minimo;
        document.getElementById("inputAuxilioTte").value = data.auxilio_tte;
        document.getElementById("inputCesantias").value = data.cesantias;
        document.getElementById("inputIntCesantias").value = data.int_cesantias;
        document.getElementById("inputPrima").value = data.prima;
        document.getElementById("inputVacaciones").value = data.vacaciones;
        document.getElementById("inputSaludEmpleado").value = data.salud_empleado;
        document.getElementById("inputSaludEmpleador").value = data.salud_empleador;
        document.getElementById("inputPensionEmpleado").value = data.pension_empleado;
        document.getElementById("inputPensionEmpleador").value = data.pension_empleador;
        document.getElementById("inputCajaCompensacion").value = data.caja_compensacion;
        document.getElementById("inputArl").value = data.arl;
        document.getElementById("inputDotacion").value = data.dotacion;
        document.getElementById("inputTotalPorcentaje").value = data.total_porcentaje;
        document.getElementById("inputCostoMensual").value = data.costo_mensual;
        document.getElementById("inputCostoHora").value = data.costo_hora;
        
        // Establecer el ID del salario como valor oculto en el formulario
        document.getElementById("formularioEditar").setAttribute("data-id", id);
    })
    .catch(error => console.error('Error al obtener datos del salario:', error));
}

// Función para cerrar el modal de edición
function cerrarModalEditar() {
    // Cerrar el modal de edición
    var modalEditar = document.getElementById("modalEditar");
    modalEditar.style.display = "none";
}

// Evento al enviar el formulario de edición
document.getElementById("formularioEditar").addEventListener("submit", function(event) {
    // Evitar que el formulario se envíe de forma tradicional
    event.preventDefault();
    
    // Mostrar mensaje de confirmación
    var confirmacion = confirm("¿Estás seguro que quieres editar este salario?");
    
    // Si el usuario confirma, proceder con la actualización del salario
    if (confirmacion) {
        // Obtener los datos del formulario
        var formData = new FormData(this);
        var id = this.getAttribute("data-id");
        
        // Realizar una solicitud AJAX para actualizar el salario con los nuevos datos
        fetch('actualizarSalario.php?id=' + id, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al actualizar el salario.');
            }
            // Cerrar el modal después de actualizar el salario
            cerrarModalEditar();
            // Recargar la página para reflejar los cambios
            location.reload();
        })
        .catch(error => console.error('Error al actualizar el salario:', error));
    }
});
