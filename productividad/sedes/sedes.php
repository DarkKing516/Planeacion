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
    <title>SEDES</title>
    <link rel="stylesheet" href="sedes.css">
</head>
<body>

<a href="/productividad/menu.php" class="menu-link">
    <button class="menu-button">
        IR AL MENÚ
    </button>
</a>

<a href="sedes.php" class="menu-link">
    <button class="menu-button">
        REFRESCAR
    </button>
</a>

<h2>CREAR UNA NUEVA SEDE</h2>
<form id="sedesForm">
    <div class="form-group">
        <label for="sede">NOMBRE DE LA SEDE:</label>
        <input type="text" id="sede" class="form-control" placeholder="INGRESE EL NOMBRE DE LA SEDE">
        <label for="per_autorizado">PERSONAL AUTORIZADO:</label>
        <input type="text" id="per_autorizado" class="form-control" placeholder="INGRESE EL PERSONAL AUTORIZADO">
    </div>
    <button type="button" id="guardarSedeBtn" class="btn btn-primary guardar-btn">GUARDAR</button>
</form>

<div class="tabla-sedes">
    <h2>TABLA DE SEDES</h2>
    <table id="tablaSedes">
        <thead>
            <tr>
                <th>NOMBRE DE LA SEDE</th>
                <th>PER. AUTORIZADO</th>
                <th>ACCIONES</th>
            </tr>
        </thead>
        <tbody>
            <!-- Aquí se cargarán los datos de la tabla dinámicamente -->
        </tbody>
    </table>
</div>

<div id="editarSedeModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>EDITAR SEDE</h2>
        <form id="editarSedeForm">
            <div class="form-group">
                <label for="sedeEditar">NUEVO NOMBRE:</label>
                <input type="text" id="sedeEditar" class="form-control" placeholder="INGRESE EL NUEVO NOMBRE DE LA SEDE">
                <label for="per_autorizadoEditar">PERSONAL AUTORIZADO:</label>
                <input type="text" id="per_autorizadoEditar" class="form-control" placeholder="INGRESE EL PERSONAL AUTORIZADO">
            </div>
            <button type="submit" id="actualizarSedeBtn" class="btn btn-primary">ACTUALIZAR</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="sedes.js"></script>
</body>
</html>
