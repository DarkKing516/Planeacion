$(document).ready(function() {
    // Función para guardar un nuevo rol
    $("#guardarRolBtn").click(function() {
        var nombreRol = $("#nombre").val();
        
        // Validar que el nombre del rol no esté vacío
        if (nombreRol.trim() === "") {
            alert("POR FAVOR INGRESE UN NOMBRE PARA EL ROL.");
            return;
        }

        // Envío de los datos del nuevo rol al backend
        $.ajax({
            url: "guardarRoles.php",
            method: "POST",
            data: { nombre: nombreRol },
            success: function(response) {
                console.log("RESPUESTA DEL SERVIDOR:", response);
                if (response.success) {
                    alert(response.message);
                    // Redirigir a la misma página
                    window.location.href = "roles.php";
                } else {
                    console.error("ERROR AL GUARDAR EL ROL:", response.message);
                    alert("OCURRIÓ UN ERROR AL GUARDAR EL ROL. POR FAVOR, INTÉNTALO DE NUEVO.");
                }
            },
            error: function(xhr, status, error) {
                console.error("ERROR AL GUARDAR EL ROL:", error);
                alert("OCURRIÓ UN ERROR AL GUARDAR EL ROL. POR FAVOR, INTÉNTALO DE NUEVO.");
            }
        });
    });

    // Función para cargar los roles desde el servidor
    function cargarRoles() {
        $.ajax({
            url: "obtenerRoles.php",
            method: "GET",
            dataType: "json",
            success: function(response) {
                // Limpiar la tabla antes de agregar los nuevos registros
                $("#tablaRoles tbody").empty();
                
                // Agregar los registros recuperados a la tabla
                response.forEach(function(rol) {
                    var fila = "<tr><td>" + rol.nombre + "</td>";
                    fila += "<td><button class='eliminar-btn btn-eliminar' data-id='" + rol.id + "'>ELIMINAR</button>";
                    fila += "<button class='editar-btn btn-editar' data-id='" + rol.id + "'>EDITAR</button></td></tr>";
                    $("#tablaRoles tbody").append(fila);
                });
            },
            error: function(xhr, status, error) {
                console.error("ERROR AL CARGAR LOS ROLES:", error);
                alert("OCURRIÓ UN ERROR AL CARGAR LOS ROLES. POR FAVOR, INTÉNTALO DE NUEVO.");
            }
        });
    }

    // Llamar a la función cargarRoles al cargar la página y luego cada cierto intervalo
    cargarRoles(); // Llamada inicial al cargar la página
    setInterval(cargarRoles, 5000); // Actualizar cada 5 segundos (puedes ajustar este valor según tus necesidades)

    // Resto del código sigue aquí...
});

$(document).ready(function() {
    // Evento clic para eliminar un rol
    $(document).on("click", ".eliminar-btn", function() {
        var idRol = $(this).data("id");
        if (confirm("¿SEGURO QUE QUIERES ELIMINAR ESTE ROL?")) {
            // Envío de la solicitud AJAX para eliminar el rol
            $.ajax({
                url: "eliminarRol.php",
                method: "POST",
                data: { id: idRol },
                success: function(response) {
                    // Recargar la tabla de roles después de eliminar
                    cargarRoles();
                    alert(response); // Mostrar mensaje de éxito o error
                },
                error: function(xhr, status, error) {
                    console.error("ERROR AL ELIMINAR EL ROL:", error);
                    alert("OCURRIÓ UN ERROR AL ELIMINAR EL ROL. POR FAVOR, INTÉNTALO DE NUEVO.");
                }
            });
        }
    });

    // Evento clic para mostrar el formulario emergente al hacer clic en el botón de editar
    $(document).on("click", ".editar-btn", function() {
        var idRol = $(this).data("id");

        // Obtener el nombre del rol correspondiente al idRol y establecerlo como valor del campo de texto
        $.ajax({
            url: "obtenerNombreRol.php",
            method: "POST",
            data: { id: idRol },
            success: function(response) {
                $("#nombreEditar").val(response);
            },
            error: function(xhr, status, error) {
                console.error("ERROR AL OBTENER EL NOMBRE DEL ROL:", error);
                alert("OCURRIÓ UN ERROR AL OBTENER EL NOMBRE DEL ROL. POR FAVOR, INTÉNTELO DE NUEVO.");
            }
        });

        // Establecer el ID del rol en el modal de edición
        $("#editarRolModal").data("id", idRol);

        // Mostrar el formulario emergente
        $("#editarRolModal").css("display", "block");
    });

    // Ocultar el formulario emergente al hacer clic en el botón de cerrar
    $(".close").click(function() {
        $("#editarRolModal").css("display", "none");
    });

    // Evento para enviar el formulario de edición al hacer clic en el botón de actualizar
    $("#editarRolForm").submit(function(event) {
        event.preventDefault(); // Evitar el comportamiento predeterminado del formulario

        var idRol = obtenerIdRol(); // Llamada a la función para obtener el id del rol en edición
        var nuevoNombre = $("#nombreEditar").val(); // Obtener el nuevo nombre del rol del campo de texto

        // Mostrar un mensaje de confirmación antes de actualizar
        if (confirm("¿SEGURO QUE QUIERES ACTUALIZAR ESTE ROL?")) {
            // Verificar si se recibió correctamente el nuevo nombre del rol
            if (nuevoNombre) {
                // Envío de los datos actualizados del rol al backend
                $.ajax({
                    url: "actualizarRol.php",
                    method: "POST",
                    data: { id: idRol, nombre: nuevoNombre },
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
    });

    // Función para obtener el id del rol en edición
    function obtenerIdRol() {
        // Obtener el ID del rol del modal de edición
        return $("#editarRolModal").data("id");
    }
});
