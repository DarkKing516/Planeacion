<?php
session_start();

// Verificar si no hay una sesión activa para el usuario
if (!isset($_SESSION['usuario'])) {
    // Redirigir al usuario de vuelta a index.php
    header("Location: index.php");
    exit(); // Asegurar que el script se detenga después de redirigir
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MENÚ.</title>
    <!-- Enlace al archivo CSS -->
    <link rel="stylesheet" type="text/css" href="menu.css">
    <!-- Referencia al conjunto de íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<div class="menu-header">
    <h1>MENÚ.</h1>
    <form action="logout.php" method="post">
    <button type="submit" name="cerrar_sesion" class="cerrar-sesion-btn">CERRAR SESIÓN</button>
</form>
</div>

<!-- Enlace al inicio de la página -->
<a href="#top">
    <img id="logo" src="imagenes/logo.png" alt="logo">
</a>

<!-- Primer contenedor de botones -->
<div class="button-container" id="button-container">
    <ul id="menu-list">
        <button onclick="mostrarSubmenu()">CONFIGURACIÓN DE MAESTRAS <i class="fas fa-chevron-down"></i></button>
        <li><button onclick="window.location.href='planeacion/planeacion.php'">PLANEACIÓN DIARIA</button></li>
        <li><button onclick="window.location.href='produccion/produccion.php'">PRODUCCIÓN DIARIA</button></li>
        <li><button onclick="window.location.href='informes/informes.php'">INFORMES</button></li>
    </ul>
</div>

<!-- Botón de "CONFIGURACIÓN DE MAESTRAS" -->
<div class="button-container">
    <ul id="submenu-container">
        <button onclick="mostrarSubmenu()">CONFIGURACIÓN DE MAESTRAS <i class="fas fa-chevron-down"></i></button>
        <li><button onclick="mostrarSubMenu2()">ARTÍCULOS <i class="fas fa-chevron-down"></i></button></li>
        <div class="second-button-container" id="second-container"> <!-- Añadido un ID al segundo contenedor -->
            <ul>
                <li><button onclick="window.location.href='articulos/articulos.php'">IMPORTAR ARTÍCULOS</button></li>
                <li><button onclick="window.location.href='articulos/crearArticulo.php'">CREAR ARTÍCULO</button></li>
                <li><button onclick="window.location.href='articulos/tablaArticulos.php'">TABLA DE ARTÍCULOS</button></li>
            </ul>
        </div>

        <li><button onclick="mostrarSubmenu3()">EMPLEADOS <i class="fas fa-chevron-down"></i></button></li>
        <div class="third-button-container" id="third-container"> <!-- Añadido un ID al tercer contenedor -->
            <ul>
                <li><button onclick="window.location.href='empleados/empleados.php'">IMPORTAR EMPLEADOS</button></li>
                <li><button onclick="window.location.href='empleados/crearEmpleado.php'">CREAR EMPLEADO</button></li>
                <li><button onclick="window.location.href='empleados/tablaEmpleados.php'">TABLA DE EMPLEADOS</button></li>
            </ul>
        </div>
        
        <li><button onclick="mostrarSubmenu4()">PROCEDIMIENTOS <i class="fas fa-chevron-down"></i></button></li>
        <div class="fourth-button-container" id="fourth-container"> <!-- Añadido un ID al tercer contenedor -->
            <ul>
                <li><button onclick="window.location.href='grupos/grupos.php'">GRUPOS</button></li>
                <li><button onclick="window.location.href='procesos/procesos.php'">PROCESOS</button></li>
                <li><button onclick="window.location.href='estandar/estandar.php'">ESTANDAR</button></li>
                <li><button onclick="window.location.href='descansos/descansos.php'">DESCANSOS</button></li>
                <li><button onclick="window.location.href='sedes/sedes.php'">SEDES</button></li>
                <li><button onclick="window.location.href='tipos/tipos.php'">TIPOS</button></li>
            </ul>
        </div>

        <!-- Botones que estaban fuera del contenedor movidos dentro -->
        <li><button onclick="mostrarSubmenu5()">SEGURIDAD <i class="fas fa-chevron-down"></i></button></li>
        <div class="fifth-button-container" id="fifth-container"> <!-- Añadido un ID al tercer contenedor -->
            <ul>
                <li><button onclick="window.location.href='usuarios/usuarios.php'">USUARIOS</button></li>
                <li><button onclick="window.location.href='roles/roles.php'">ROLES</button></li>
                <li><button onclick="window.location.href='permisos/permisos.php'">PERMISOS</button></li>
            </ul>
        </div>

        <li><button onclick="window.location.href='salarios/salarios.php'">SALARIOS</button></li>
    </ul>
</div>

<!-- Enlace al archivo JavaScript -->
<script src="menu.js"></script>
</body>
</html>
