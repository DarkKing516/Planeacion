<?php
session_start();

$usuario = $_POST['usuario'];
$clave = $_POST['clave'];

include("conexion.php");

$consulta = "SELECT * FROM usuario WHERE usuario = '$usuario' AND clave = '$clave'";
$resultado = $conn->query($consulta);

if ($resultado->num_rows > 0) {
    $_SESSION['usuario'] = $usuario;
    header("Location: menu.php");
    exit();
} else {
    echo "<script>alert('USUARIO O CONTRASEÑA INCORRECTOS. POR FAVOR, INTÉNTALO DE NUEVO.'); window.location.href = 'index.php';</script>";
}
$conn->close();
?>
