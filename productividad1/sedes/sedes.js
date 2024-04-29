$(document).ready(function() {
    // Función para guardar una nueva sede
    $("#guardarSedeBtn").click(function() {
        var nombreSede = $("#sede").val();
        var perAutorizado = $("#per_autorizado").val();
        
        // Validar que los campos no estén vacíos
        if (nombreSede.trim() === "" || perAutorizado.trim() === "") {
            alert("POR FAVOR INGRESE EL NOMBRE Y NÚMERO DE PERSONAL AUTORIZADO PARA LA SEDE.");
            return;
        }

        // Envío de los datos de la nueva sede al backend
        $.ajax({
            url: "guardarSede.php",
            method: "POST",
            data: { sede: nombreSede, per_autorizado: perAutorizado }, // Cambiado aquí
            success: function(response) {
                console.log("RESPUESTA DEL SERVIDOR:", response);
                alert(response);
                // Redirigir a la misma página después de guardar
                window.location.href = "sedes.php";
            },
            error: function(xhr, status, error) {
                console.error("ERROR AL GUARDAR LA SEDE:", error);
                alert("OCURRIÓ UN ERROR AL GUARDAR LA SEDE. POR FAVOR, INTÉNTELO DE NUEVO.");
            }
        });
    });

    // Función para cargar las sedes desde el servidor
    function cargarSedes() {
        $.ajax({
            url: "obtenerSedes.php",
            method: "GET",
            dataType: "json",
            success: function(response) {
                // Limpiar la tabla antes de agregar los nuevos registros
                $("#tablaSedes tbody").empty();
                
                // Agregar los registros recuperados a la tabla
                response.forEach(function(sede) {
                    var fila = "<tr><td>" + sede.sede + "</td>";
                    fila += "<td>" + sede.per_autorizado + "</td>";
                    fila += "<td><button class='eliminar-btn btn-eliminar' data-id='" + sede.id + "'>ELIMINAR</button>";
                    fila += "<button class='editar-btn btn-editar' data-id='" + sede.id + "'>EDITAR</button></td></tr>";
                    $("#tablaSedes tbody").append(fila);
                });
            },
            error: function(xhr, status, error) {
                console.error("ERROR AL CARGAR LAS SEDES:", error);
                alert("OCURRIÓ UN ERROR AL CARGAR LA SEDE. POR FAVOR, INTÉNTALO DE NUEVO.");
            }
        });
    }

    // Llamar a la función cargarSedes al cargar la página y luego cada cierto intervalo
    cargarSedes(); // Llamada inicial al cargar la página
    setInterval(cargarSedes, 5000); // Actualizar cada 5 segundos (puedes ajustar este valor según tus necesidades)

    // Evento clic para eliminar una sede
    $(document).on("click", ".eliminar-btn", function() {
        var idSede = $(this).data("id");
        if (confirm("¿ESTÁS SEGURO QUE QUIERES ELIMINAR ESTA SEDE?")) {
            // Envío de la solicitud AJAX para eliminar la sede
            $.ajax({
                url: "eliminarSede.php",
                method: "POST",
                data: { id: idSede },
                success: function(response) {
                    // Recargar la tabla de sedes después de eliminar
                    cargarSedes();
                    alert(response); // Mostrar mensaje de éxito o error
                },
                error: function(xhr, status, error) {
                    console.error("ERROR AL ELIMINAR LA SEDE:", error);
                    alert("OCURRIÓ UN ERROR AL ELIMINAR LA SEDE. POR FAVOR, INTÉNTALO DE NUEVO.");
                }
            });
        }
    });

    // Evento clic para mostrar el formulario emergente al hacer clic en el botón de editar
    $(document).on("click", ".editar-btn", function() {
        var idSede = $(this).data("id");

        // Obtener los datos de la sede correspondiente al idSede
        $.ajax({
            url: "obtenerNombreSede.php",
            method: "POST",
            data: { id: idSede },
            success: function(response) {
                var sede = JSON.parse(response);
                $("#sedeEditar").val(sede.sede);
                $("#per_autorizadoEditar").val(sede.per_autorizado);
            },
            error: function(xhr, status, error) {
                console.error("ERROR AL OBTENER EL NOMBRE DE LA SEDE:", error);
                alert("OOCURRIÓ UN ERROR AL OBTENER EL NOMBRE DE LA SEDE. POR FAVOR, INTÉNTALO DE NUEVO.");
            }
        });

        // Establecer el ID de la sede en el modal de edición
        $("#editarSedeModal").data("id", idSede);

        // Mostrar el formulario emergente
        $("#editarSedeModal").css("display", "block");
    });

    // Ocultar el formulario emergente al hacer clic en el botón de cerrar
    $(".close").click(function() {
        $("#editarSedeModal").css("display", "none");
    });

    // Evento para enviar el formulario de edición al hacer clic en el botón de actualizar
    $("#editarSedeForm").submit(function(event) {
        event.preventDefault(); // Evitar el comportamiento predeterminado del formulario

        // Mostrar confirmación antes de enviar el formulario
        if (confirm("¿ESTÁS SEGURO QUE QUIERES EDITAR ESTA SEDE?")) {
            var idSede = obtenerIdSede(); // Obtener el ID de la sede en edición
            var nuevaSede = $("#sedeEditar").val(); // Obtener el nuevo nombre de la sede del campo de texto
            var nuevoPerAutorizado = $("#per_autorizadoEditar").val(); // Obtener el nuevo número de personal autorizado del campo de texto

            // Verificar si se recibió correctamente el nuevo nombre de la sede
            if (nuevaSede) {
                // Envío de los datos actualizados de la sede al backend
                $.ajax({
                    url: "editarSede.php",
                    method: "POST",
                    data: { id: idSede, sede: nuevaSede, per_autorizado: nuevoPerAutorizado },
                    success: function(response) {
                        // Cerrar el formulario emergente
                        $("#editarSedeModal").css("display", "none");
                        // Recargar la tabla de sedes después de actualizar
                        cargarSedes();
                        alert(response); // Mostrar mensaje de éxito o error
                    },
                    error: function(xhr, status, error) {
                        console.error("ERROR AL ACTUALIZAR LA SEDE:", error);
                        alert("OCURRIÓ UN ERROR AL ACTUALIZAR LA SEDE. POR FAVOR, INTÉNTALO DE NUEVO.");
                    }
                });
            } else {
                // Mostrar una alerta si falta el nuevo nombre de la sede
                alert("NO SE RECIBIÓ EL NUEVO NOMBRE DE LA SEDE.");
            }
        }
    });

    // Función para obtener el ID de la sede en edición desde el modal
    function obtenerIdSede() {
        return $("#editarSedeModal").data("id");
    }
});
