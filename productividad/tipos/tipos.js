// Esperar a que el documento esté completamente cargado
$(document).ready(function() {
    // Función para guardar un nuevo tipo
    $("#guardarTipoBtn").click(function() {
        // Obtener el nombre del tipo desde el campo de entrada
        var nombreTipo = $("#nombre").val();
        
        // Validar que el nombre del tipo no esté vacío
        if (nombreTipo.trim() === "") {
            alert("POR FAVOR INGRESE UN NOMBRE PARA EL TIPO.");
            return;
        }

        // Envío de los datos del nuevo tipo al backend
        $.ajax({
            url: "guardarTipos.php",
            method: "POST",
            data: { nombre: nombreTipo },
            success: function(response) {
                // Mostrar mensaje de éxito o error
                console.log("RESPUESTA DEL SERVIDOR:", response);
                if (response.success) {
                    alert(response.message);
                    // Redirigir a la misma página después de guardar
                    window.location.href = "tipos.php";
                } else {
                    console.error("ERROR AL GUARDAR EL TIPO:", response.message);
                    alert("OCURRIÓ UN ERROR AL GUARDAR EL TIPO. POR FAVOR, INTÉNTALO DE NUEVO.");
                }
            },
            error: function(xhr, status, error) {
                console.error("ERROR AL GUARDAR EL TIPO:", error);
                alert("OCURRIÓ UN ERROR AL GUARDAR EL TIPO. POR FAVOR, INTÉNTALO DE NUEVO.");
            }
        });
    });

    // Función para cargar los tipos desde el servidor
    function cargarTipos() {
        $.ajax({
            url: "obtenerTipos.php",
            method: "GET",
            dataType: "json",
            success: function(response) {
                // Limpiar la tabla antes de agregar los nuevos registros
                $("#tablaTipos tbody").empty();
                
                // Agregar los registros recuperados a la tabla
                response.forEach(function(tipo) {
                    var fila = "<tr><td>" + tipo.nombre + "</td>";
                    fila += "<td><button class='eliminar-btn btn-eliminar' data-id='" + tipo.id + "'>ELIMINAR</button>";
                    fila += "<button class='editar-btn btn-editar' data-id='" + tipo.id + "'>EDITAR</button></td></tr>";
                    $("#tablaTipos tbody").append(fila);
                });
            },
            error: function(xhr, status, error) {
                console.error("ERROR AL CARGAR LOS TIPOS:", error);
                alert("OCURRIÓ UN ERROR AL CARGAR LOS TIPOS. POR FAVOR, INTÉNTALO DE NUEVO.");
            }
        });
    }

    // Llamar a la función cargarTipos al cargar la página y luego cada cierto intervalo
    cargarTipos(); // Llamada inicial al cargar la página
    setInterval(cargarTipos, 5000); // Actualizar cada 5 segundos (puedes ajustar este valor según tus necesidades)

    // Función para eliminar un tipo
    $(document).on("click", ".eliminar-btn", function() {
        var idTipo = $(this).data("id");
        if (confirm("¿SEGURO QUE QUIERES ELIMINAR ESTE TIPO?")) {
            // Envío de la solicitud AJAX para eliminar el tipo
            $.ajax({
                url: "eliminarTipo.php",
                method: "POST",
                data: { id: idTipo },
                success: function(response) {
                    // Recargar la tabla de tipos después de eliminar
                    cargarTipos();
                    alert(response); // Mostrar mensaje de éxito o error
                },
                error: function(xhr, status, error) {
                    console.error("ERROR AL ELIMINAR EL TIPO:", error);
                    alert("OCURRIÓ UN ERROR AL ELIMINAR EL TIPO. POR FAVOR, INTÉNTALO DE NUEVO.");
                }
            });
        }
    });

    // Función para mostrar el formulario de edición al hacer clic en el botón de editar
    $(document).on("click", ".editar-btn", function() {
        var idTipo = $(this).data("id");

        // Verifica si el ID del tipo se está capturando correctamente
        console.log("ID del tipo:", idTipo);

        // Obtener el nombre del tipo correspondiente al idTipo y establecerlo como valor del campo de texto
        $.ajax({
            url: "obtenerNombreTipo.php",
            method: "POST",
            data: { id: idTipo },
            success: function(response) {
                $("#nombreEditar").val(response);
            },
            error: function(xhr, status, error) {
                console.error("ERROR AL OBTENER EL NOMBRE DEL TIPO:", error);
                alert("OCURRIÓ UN ERROR AL OBTENER EL NOMBRE DEL TIPO. POR FAVOR, INTÉNTELO DE NUEVO.");
            }
        });

        // Establecer el ID del tipo en el modal de edición
        $("#editarTipoModal").data("id", idTipo);

        // Mostrar el formulario emergente
        $("#editarTipoModal").css("display", "block");
    });

    // Ocultar el formulario emergente al hacer clic en el botón de cerrar
    $(".close").click(function() {
        $("#editarTipoModal").css("display", "none");
    });

    // Evento para enviar el formulario de edición al hacer clic en el botón de actualizar
    $("#editarTipoForm").submit(function(event) {
        event.preventDefault(); // Evitar el comportamiento predeterminado del formulario

        var idTipo = obtenerIdTipo(); // Llamada a la función para obtener el id del tipo en edición

        // Verifica si el ID del tipo se está pasando correctamente
        console.log("ID del tipo al enviar el formulario:", idTipo);

        var nuevoNombre = $("#nombreEditar").val(); // Obtener el nuevo nombre del tipo del campo de texto

        // Mostrar un mensaje de confirmación antes de actualizar
        if (confirm("¿SEGURO QUE QUIERES ACTUALIZAR ESTE TIPO?")) {
            // Verificar si se recibió correctamente el nuevo nombre del tipo
            if (nuevoNombre) {
                // Envío de los datos actualizados del tipo al backend
                $.ajax({
                    url: "actualizarTipo.php",
                    method: "POST",
                    data: { id: idTipo, nombre: nuevoNombre },
                    success: function(response) {
                        // Cerrar el formulario emergente
                        $("#editarTipoModal").css("display", "none");
                        // Recargar la tabla de tipos después de actualizar
                        cargarTipos();
                        alert(response); // Mostrar mensaje de éxito o error
                    },
                    error: function(xhr, status, error) {
                        console.error("ERROR AL ACTUALIZAR EL TIPO:", error);
                        alert("OCURRIÓ UN ERROR AL ACTUALIZAR EL TIPO. POR FAVOR, INTÉNTELO DE NUEVO.");
                    }
                });
            } else {
                // Mostrar una alerta si falta el nuevo nombre del tipo
                alert("NO SE RECIBIÓ EL NUEVO NOMBRE DEL TIPO.");
            }
        }
    });

    // Función para obtener el id del tipo en edición
    function obtenerIdTipo() {
        // Obtener el ID del tipo del modal de edición
        return $("#editarTipoModal").data("id");
    }
});
