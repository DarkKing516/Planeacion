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
// Verificar si se recibió el id, el nuevo nombre y el nuevo grupo del proceso
if(isset($_POST['id']) && isset($_POST['nuevoNombre']) && isset($_POST['nuevoGrupo'])) {
    // Conexión a la base de datos
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "datosgenerales";

    $conn = new mysqli($server, $user, $pass, $db);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("ERROR DE CONEXIÓN: " . $conn->connect_error);
    }

    // Preparar la consulta SQL para actualizar el nombre y el grupo del proceso
    $idProceso = $_POST['id'];
    $nuevoNombre = $_POST['nuevoNombre'];
    $nuevoGrupo = $_POST['nuevoGrupo'];
    $sql = "UPDATE procesos SET nombre = '$nuevoNombre', grupo = '$nuevoGrupo' WHERE id = $idProceso"; // Cambiado a UPDATE

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        // Mostrar la alerta y redirigir
        echo "<script>alert('EL NOMBRE Y GRUPO DEL PROCESO SE HAN ACTUALIZADO CORRECTAMENTE'); window.location.href = 'procesos.php';</script>";
    } else {
        echo "ERROR AL ACTUALIZAR EL NOMBRE Y GRUPO DEL PROCESO: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "NO SE RECIBIÓ EL ID, EL NUEVO NOMBRE Y EL NUEVO GRUPO DEL PROCESO.";
}
?>
