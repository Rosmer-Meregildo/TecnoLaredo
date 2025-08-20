<?php
// --- conexion.php ---

$host = "localhost";
$user = "root";
$pass = "";
$db = "tecnolaredo";

$con = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($con->connect_error) {
    die("Conexión fallida: " . $con->connect_error);
}
?>