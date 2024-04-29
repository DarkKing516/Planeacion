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
    <title>ROLES</title>
    <link rel="stylesheet" href="roles.css">
</head>
<body>

<a href="/productividad/menu.php" class="menu-link">
    <button class="menu-button">
        IR AL MENÚ
    </button>
</a>

<a href="roles.php" class="menu-link">
    <button class="menu-button">
        REFRESCAR
    </button>
</a>

<h2>CREAR UN NUEVO ROL</h2>
<form id="rolesForm">
    <div class="form-group">
        <label for="nombre">NOMBRE DEL ROL:</label>
        <input type="text" id="nombre" class="form-control" placeholder="INGRESE EL NOMBRE DEL ROL">
    </div>
    <button type="button" id="guardarRolBtn" class="btn btn-primary guardar-btn">GUARDAR</button>
</form>

<div class="tabla-roles">
    <h2>TABLA DE ROLES</h2>
    <table id="tablaRoles">
        <thead>
            <tr>
                <th>NOMBRE DEL ROL</th>
                <th>ACCIONES</th>
            </tr>
        </thead>
        <tbody>
            <!-- Aquí se cargarán los datos de la tabla dinámicamente -->
        </tbody>
    </table>
</div>

<div id="editarRolModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>EDITAR ROL</h2>
        <form id="editarRolForm">
            <div class="form-group">
                <label for="nombreEditar">NUEVO NOMBRE:</label>
                <input type="text" id="nombreEditar" class="form-control" placeholder="INGRESE EL NUEVO NOMBRE DEL ROL">
            </div>
            <button type="submit" id="actualizarRolBtn" class="btn btn-primary">ACTUALIZAR</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="roles.js"></script>
</body>
</html>
