<?php
// includes/functions.php

function redirect($url) {
    header("Location: " . $url);
    exit();
}

function isAuthenticated() {
    return isset($_SESSION['user_id']);
}

function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getUserRole() {
    return $_SESSION['role'] ?? 'guest';
}
?>