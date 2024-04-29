<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejemplo Formulario</title>
    <link rel="stylesheet" href="ejemplo.css">
</head>
<body>
    <h1>Formulario de Ejemplo</h1>
    <form action="ejemploGuardar.php" method="post">
        <!-- Campos existentes -->
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        
        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" required>
        
        <label for="sexo">Sexo:</label>
        <select id="sexo" name="sexo" required>
            <option value="Masculino">Masculino</option>
            <option value="Femenino">Femenino</option>
        </select>
        
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" required>
        
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required>
        
        <!-- Campos adicionales (inicialmente ocultos) -->
        <div id="additional-fields">
            <div class="additional-field">
                <label for="telefono">Teléfono:</label>
                <input type="tel" name="telefono[]" required>
                
                <label for="estudio">Estudio:</label>
                <input type="text" name="estudio[]" required>
                
                <label for="actividad">Actividad:</label>
                <input type="text" name="actividad[]" required>
            </div>
        </div>
        
        <!-- Botón de agregar campo adicional -->
        <button type="button" id="add-more">+</button>
        
        <!-- Botón de guardar -->
        <button type="submit">Guardar</button>
    </form>

    <script src="ejemplo.js"></script>

    <script>
        // Función para obtener el valor de un parámetro de consulta en la URL
        function obtenerParametro(nombre) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.has(nombre) ? urlParams.get(nombre) : false;
        }

        // Verificar si se ha guardado correctamente y mostrar el mensaje de alerta
        window.addEventListener('load', function() {
            const guardado = obtenerParametro('guardado');
            if (guardado && guardado === 'true') {
                alert('Los datos se han guardado correctamente.');
            }
        });
    </script>
</body>
</html>
