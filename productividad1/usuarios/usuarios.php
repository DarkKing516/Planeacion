<?php
// Iniciar sesión
session_start();

// Verificar si no hay una sesión activa para el usuario
if (!isset($_SESSION['usuario'])) {
    // Mostrar mensaje de alerta
    echo "<script>alert('DEBES INICIAR SESIÓN PARA PODER INGRESAR A ESTA PÁGINA');</script>";
    
    // Redirigir al usuario de vuelta a la página de inicio de sesión
    header("Location: /productividad/index.php");
    exit(); // Asegurar que el script se detenga después de redirigir
}
?>

<?php
// Establecer conexión a la base de datos
$server = "localhost";
$user = "root";
$pass = "";
$db = "datosgenerales";
$conn = new mysqli($server, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("ERROR DE CONEXIÓN: " . $conn->connect_error);
}

// Consulta SQL para obtener los registros de la tabla 'roles'
$sql_roles = "SELECT DISTINCT nombre FROM roles";
$resultado_roles = $conn->query($sql_roles);
$optionsRoles = '';
if ($resultado_roles->num_rows > 0) {
    while ($row = $resultado_roles->fetch_assoc()) {
        $optionsRoles .= "<option value='{$row['nombre']}'>{$row['nombre']}</option>";
    }
}

// Consulta SQL para obtener los registros de la tabla 'usuario'
$sql_usuarios = "SELECT cedula, usuario, rol FROM usuario";
$resultado_usuarios = $conn->query($sql_usuarios);

$registros_usuarios = '';
if ($resultado_usuarios->num_rows > 0) {
    // Construir la tabla de registros de usuarios
    $registros_usuarios .= "<table border='1'>
                            <tr>
                                <th>CÉDULA</th>
                                <th>USUARIO</th>
                                <th>ROL</th>
                                <th>ELIMINAR</th>
                            </tr>";
    while ($row = $resultado_usuarios->fetch_assoc()) {
        $registros_usuarios .= "<tr>
                                    <td>{$row['cedula']}</td>
                                    <td>{$row['usuario']}</td>
                                    <td>{$row['rol']}</td>
                                    <td><button class='eliminar-button' onclick=\"eliminarUsuario('{$row['cedula']}')\">ELIMINAR</button></td>
                                </tr>";
    }
    $registros_usuarios .= "</table>";
} else {
    $registros_usuarios = "<p>NO HAY REGISTROS ALMACENADOS EN LA TABLA.</p>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTIÓN DE USUARIOS</title>
    <link rel="stylesheet" href="usuarios.css">
    <script>
    function eliminarUsuario(cedula) {
        if (confirm("¿ESTÁS SEGURO QUE QUIERES ELIMINAR ESTE USUARIO?")) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'eliminarUsuario.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                console.log(xhr.responseText); // Verificar la respuesta del servidor en la consola
                mostrarMensaje(xhr.responseText, 'eliminar'); // Llamar a la función mostrarMensaje con la respuesta del servidor y el tipo de acción
            };
            xhr.send('cedula=' + encodeURIComponent(cedula));
        }
    }

    function mostrarMensaje(respuesta, tipo) {
        if (tipo === 'crear') {
            if (respuesta.trim() === 'exito') {
                alert("USUARIO CREADO CORRECTAMENTE.");
                window.location.reload(); // Recargar la página para reflejar los cambios
            } else {
                alert("ERROR AL CREAR EL USUARIO: " + respuesta);
            }
        } else if (tipo === 'eliminar') {
            if (respuesta.trim() === 'exito') {
                alert("USUARIO ELIMINADO CORRECTAMENTE.");
                window.location.reload(); // Recargar la página para reflejar los cambios
            } else {
                alert("ERROR AL ELIMINAR EL USUARIO: " + respuesta);
            }
        }
    }

    function togglePassword(inputId, buttonId) {
        var passwordField = document.getElementById(inputId);
        var toggleButton = document.getElementById(buttonId);

        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleButton.textContent = "Ocultar";
        } else {
            passwordField.type = "password";
            toggleButton.textContent = "Mostrar";
        }
    }
</script>
</head>
<body>

<a href="/productividad/menu.php">
    <button>
        IR AL MENÚ
    </button>
</a>

<a href="usuarios.php"><button type="button">REFRESCAR</button></a>

<h1>GESTIÓN DE USUARIOS</h1>

<form action="guardarUsuario.php" method="POST" onsubmit="event.preventDefault(); 
    var formData = new FormData(this);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', this.action, true);
    xhr.onload = function () {
        console.log(xhr.responseText); // Verificar la respuesta del servidor en la consola
        mostrarMensaje(xhr.responseText); // Llamar a la función mostrarMensaje con la respuesta del servidor
    };
    xhr.send(formData);">
    <div class="form-container">
        <div class="form-group">
            <label for="cedula">CÉDULA:</label>
            <input type="text" id="cedula" name="cedula" required>
        </div>
        <div class="form-group">
            <label for="usuario">NOMBRE:</label>
            <input type="text" id="usuario" name="usuario" required>
        </div>
        <div class="form-group">
            <label for="rol">ROL:</label>
            <select id="rol" name="rol" required>
                <option value="" selected disabled>SELECCIONE UN ROL</option>
                <?php echo $optionsRoles; ?>
            </select>
        </div>
        <div class="form-group password-container">
            <label for="clave">CONTRASEÑA:</label>
            <input type="password" id="clave" name="clave" required>
            <button type="button" class="toggle-password" onclick="togglePassword('clave', 'toggleButton1')">O</button>
        </div>
        <div class="form-group password-container">
            <label for="confirmClave">CONFIRMAR CONTRASEÑA:</label>
            <input type="password" id="confirmClave" name="confirmClave" required>
            <button type="button" class="toggle-password" onclick="togglePassword('confirmClave', 'toggleButton2')">O</button>
        </div>
        <button type="submit">GUARDAR</button>
    </div>
</form>

<h1>REGISTROS ALMACENADOS EN LA TABLA DE USUARIOS</h1>
<?php echo $registros_usuarios; ?>

</body>
</html>
