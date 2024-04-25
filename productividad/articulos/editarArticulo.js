function confirmUpdate() {
    var idArticulo = obtenerIdRol(); // Llamada a la función para obtener el id del rol en edición
    var nuevoNombre = $("#nombreEditar").val(); // Obtener el nuevo nombre del rol del campo de texto

    // Mostrar un mensaje de confirmación antes de actualizar
    if (confirm("¿SEGURO QUE QUIERES ACTUALIZAR ESTE ROL?")) {
        // Verificar si se recibió correctamente el nuevo nombre del rol
        if (nuevoNombre) {
            // Envío de los datos actualizados del rol al backend
            $.ajax({
                url: "actualizarArticulo.php",
                method: "POST",
                data: { id: idArticulo, nombre: nuevoNombre },
                success: function(response) {
                    // Cerrar el formulario emergente
                    $("#editarRolModal").css("display", "none");
                    // Recargar la tabla de roles después de actualizar
                    cargarRoles();
                    alert(response); // Mostrar mensaje de éxito o error
                },
                error: function(xhr, status, error) {
                    console.error("ERROR AL ACTUALIZAR EL ROL:", error);
                    alert("OCURRIÓ UN ERROR AL ACTUALIZAR EL ROL. POR FAVOR, INTÉNTELO DE NUEVO.");
                }
            });
        } else {
            // Mostrar una alerta si falta el nuevo nombre del rol
            alert("NO SE RECIBIÓ EL NUEVO NOMBRE DEL ROL.");
        }
    }
    }
    