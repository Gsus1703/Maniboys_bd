<?php
// public/logout.php
session_start();
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión
header("Location: login.php"); // Redirige a la página de login
exit();
?>