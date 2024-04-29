<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRODUCTIVIDAD</title>
    <!-- Enlazar CSS externo -->
    <link rel="stylesheet" href="index.css">
</head>
<body>

<div class="header">
    <h1>PRODUCTIVIDAD</h1>
    <button id="openModalBtn">INICIAR SESIÓN</button>
</div>

<!-- Formulario emergente -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form action="validar.php" method="POST">
            <h2>INICIAR SESIÓN</h2>
            <input type="text" name="usuario" placeholder="USUARIO" required>
            <input type="password" name="clave" id="inicio_clave" placeholder="CONTRASEÑA" required>
            <button type="button" class="show-password-button" onclick="mostrarContrasena('inicio_clave')"> O </button>
            <input type="submit" value="INICIAR SESIÓN" class="submit-button">
        </form>
    </div>
</div>

    <img id="logo" src="imagenes/logo.png" alt="Logo">

<!-- Script para el comportamiento del formulario emergente -->
<script src="index.js"></script>
</body>
</html>
