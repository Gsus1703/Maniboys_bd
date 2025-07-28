<?php
// public/dashboard.php
session_start();
require_once __DIR__ . '/../includes/functions.php';

// Asegurarse de que el usuario esté autenticado
if (!isAuthenticated()) {
    redirect('login.php');
}

$pageTitle = "Dashboard - Maniboys";
require_once __DIR__ . '/../includes/header.php';
?>

        <h1>Bienvenido al Panel de Administración de Maniboys, <?php echo $_SESSION['username'] ?? 'Usuario'; ?>!</h1>
        <p>Tu rol: <?php echo $_SESSION['role'] ?? 'No Definido'; ?></p>

        <h2>Navegación Rápida</h2>
        <ul>
            <li><a href="products.php">Gestionar Productos</a></li>
            <li><a href="#">Gestionar Ventas (Próximamente)</a></li>
            <li><a href="#">Gestionar Clientes (Próximamente)</a></li>
            <li><a href="#">Gestionar Proveedores (Próximamente)</a></li>
        </ul>
        <p>Desde aquí podrás acceder a todas las funcionalidades del sistema.</p>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>