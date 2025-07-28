<?php
// includes/header.php
// Este archivo se incluirá en todas las páginas que requieran cabecera y navegación.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Maniboys Admin'; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="dashboard.php">Inicio</a></li>
            <li><a href="products.php">Productos</a></li>
            <li><a href="#">Reportes</a></li>
            <li><a href="logout.php">Cerrar Sesión (<?php echo $_SESSION['username'] ?? ''; ?>)</a></li>
        </ul>
    </nav>
    <div class="container">