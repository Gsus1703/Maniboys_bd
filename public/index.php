<?php
// public/index.php
session_start();
require_once __DIR__ . '/../includes/functions.php';

if (!isAuthenticated()) {
    redirect('login.php');
} else {
    redirect('dashboard.php');
}
?>