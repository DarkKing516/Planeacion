window.onload = function() {
    var principales = document.getElementById('principales');
    var nodoSelect = document.getElementById('nodo');
    var moduloSelect = document.getElementById('modulo');

    var nodos = {
        configMaestras: ['ARTÍCULOS', 'EMPLEADOS', 'PROCEDIMIENTOS', 'SEGURIDAD', 'SALARIOS'],
        planeacion: ['PLANEACIÓN DIARIA'],
        produccion: ['PRODUCCIÓN DIARIA'],
        informes: ['INFORMES']
    };

    var modulos = {
        'ARTÍCULOS': ['IMPORTAR ARTÍCULOS', 'CREAR ARTÍCULO', 'TABLA ARTÍCULOS'],
        'EMPLEADOS': ['IMPORTAR EMPLEADOS', 'CREAR EMPLEADO', 'TABLA EMPLEADOS'],
        'PROCEDIMIENTOS': ['GRUPOS', 'PROCESOS', 'ESTÁNDAR', 'DESCANSOS', 'SEDES', 'TIPOS'],
        'SEGURIDAD': ['USUARIOS', 'ROLES', 'PERMISOS'],
        'SALARIOS': ['SALARIOS'],
        'PLANEACIÓN DIARIA': ['PLANEACIÓN DIARIA'],
        'PRODUCCIÓN DIARIA': ['PRODUCCIÓN DIARIA'],
        'INFORMES': ['INFORMES']
    };

    principales.addEventListener('change', function() {
        var selectedPrincipal = this.value;

        // Limpiar opciones anteriores
        nodoSelect.innerHTML = '';
        moduloSelect.innerHTML = '';

        // Llenar el select de nodos si se ha seleccionado una opción válida
        if (selectedPrincipal !== "") {
            nodoSelect.disabled = false; // Habilitar el select de nodo
            var defaultOption = document.createElement('option');
            defaultOption.textContent = "SELECCIONE UNA OPCIÓN";
            nodoSelect.appendChild(defaultOption);
            nodos[selectedPrincipal].forEach(function(opcion) {
                var opcionElemento = document.createElement('option');
                opcionElemento.textContent = opcion;
                nodoSelect.appendChild(opcionElemento);
            });
        } else {
            nodoSelect.disabled = true; // Deshabilitar el select de nodo
        }
    });

    nodoSelect.addEventListener('change', function() {
        var selectedNodo = this.value;

        // Limpiar opciones anteriores
        moduloSelect.innerHTML = '';

        // Llenar el select de módulos si se ha seleccionado una opción válida
        if (selectedNodo !== "") {
            moduloSelect.disabled = false; // Habilitar el select de módulo
            var defaultOption = document.createElement('option');
            defaultOption.textContent = "SELECCIONE UNA OPCIÓN";
            moduloSelect.appendChild(defaultOption);
            modulos[selectedNodo].forEach(function(opcion) {
                var opcionElemento = document.createElement('option');
                opcionElemento.textContent = opcion;
                moduloSelect.appendChild(opcionElemento);
            });
        } else {
            moduloSelect.disabled = true; // Deshabilitar el select de módulo
        }
    });

    // Obtener referencia al botón "GUARDAR"
    var guardarBtn = document.getElementById('guardarBtn');

    // Agregar un event listener al botón "GUARDAR"
    guardarBtn.addEventListener('click', function() {
        // Obtener el valor seleccionado del elemento <select> principales
        var principal = principales.value;
        var nodo = nodoSelect.value;
        var modulo = moduloSelect.value;
        var rol = document.getElementById('rol').value;

        // Crear un objeto con los datos a enviar
        var datos = {
            principales: principal,
            nodo: nodo,
            modulo: modulo,
            rol: rol
        };

        // Realizar la petición AJAX al archivo PHP para guardar los datos
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'guardarPermiso.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log(xhr.responseText); // Mostrar la respuesta del servidor en la consola
                // Verificar si la respuesta es "DATOS GUARDADOS CORRECTAMENTE"
                if (xhr.responseText === "DATOS GUARDADOS CORRECTAMENTE") {
                    // Mostrar la alerta
                    alert("DATOS GUARDADOS CORRECTAMENTE");
                }
            }
        };
        // Enviar los datos del formulario al servidor
        xhr.send('principales=' + encodeURIComponent(principal) + '&nodo=' + encodeURIComponent(nodo) + '&modulo=' + encodeURIComponent(modulo) + '&rol=' + encodeURIComponent(rol));
    });

};
