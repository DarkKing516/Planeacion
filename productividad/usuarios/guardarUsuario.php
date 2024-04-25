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

// Recibir datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y limpiar los datos recibidos del formulario
    $cedula = validarEntrada($_POST['cedula']);
    $usuario = validarEntrada($_POST['usuario']);
    $rol = validarEntrada($_POST['rol']);
    $clave = validarEntrada($_POST['clave']);
    $confirmClave = validarEntrada($_POST['confirmClave']);

    // Verificar si se recibieron todos los datos necesarios
    if (!empty($cedula) && !empty($usuario) && !empty($rol) && !empty($clave) && !empty($confirmClave)) {
        // Verificar si las contraseñas coinciden
        if ($clave !== $confirmClave) {
            echo "LAS CONTRASEÑAS NO COINCIDEN.";
            exit();
        }

        // Hashear la contraseña antes de guardarla en la base de datos (por seguridad)
        $hashed_password = password_hash($clave, PASSWORD_DEFAULT);

        // Consulta SQL para insertar el nuevo usuario en la base de datos
        $sql = "INSERT INTO usuario (cedula, usuario, rol, clave) VALUES ('$cedula', '$usuario', '$rol', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            echo "exito"; // Enviar respuesta de éxito al cliente
        } else {
            echo "ERROR AL GUARDAR EL USUARIO: " . $conn->error; // Enviar mensaje de error
        }
    } else {
        echo "POR FAVOR COMPLETE TODOS LOS CAMPOS DEL FORMULARIO.";
    }
} else {
    echo "ACCESO NO AUTORIZADO";
}

// Función para validar la entrada y prevenir inyección SQL
function validarEntrada($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
