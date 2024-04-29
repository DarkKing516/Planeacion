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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INFORMES.</title>
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="informes.css">
</head>
<body>
    <a href="/productividad/menu.php" class="menu-link">
        <button class="menu-button">IR AL MENÚ</button>
    </a>

    <h1>INFORMES.</h1>

    <!-- Contenedor de botones -->
    <div class="botones-container">
        <a href="/productividad/articulos/infoArticulos.php"><button>ARTÍCULOS</button></a>
        <a href="/productividad/empleados/infoEmpleados.php"><button>EMPLEADOS</button></a>
        <a href="/productividad/estandar/infoEstandar.php"><button>ESTÁNDAR</button></a>
        <a href="/productividad/planeacion/infoPlaneacion.php"><button>PLANEACIÓN DIARIA</button></a>
        <a href="/productividad/produccion/infoProduccion.php"><button>PRODUCCIÓN DIARIA</button></a>
        <a href="productividad.php"><button>PRODUCTIVIDAD</button></a>
    </div>

</body>
</html>
