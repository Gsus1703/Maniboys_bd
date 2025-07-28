<?php
// includes/db_connection.php

$host = 'localhost';
$db = 'Maniboy_db'; // Cambia esto al nombre de tu base de datos
$user = 'root'; // Cambia esto a tu usuario de MySQL
$pass = ''; // Cambia esto a tu contraseña de MySQL
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>