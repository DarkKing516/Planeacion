<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "datosgenerales";

$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) {
    die("ERROR DE CONEXIÓN: " . $conn->connect_error);
}
?>
