<?php
// Inicia la sesión para poder destruirla
session_start();

// Destruye todas las variables de sesión
$_SESSION = array();

// Finalmente, destruye la sesión
session_destroy();

// Redirige al usuario a la página de inicio de sesión
header("Location: login.php");
exit();
?>
