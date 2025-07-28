<?php
// public/register.php
session_start();
require_once __DIR__ . '/../includes/db_connection.php';
require_once __DIR__ . '/../includes/functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = '<div class="message error">Todos los campos son obligatorios.</div>';
    } elseif ($password !== $confirm_password) {
        $message = '<div class="message error">Las contraseñas no coinciden.</div>';
    } elseif (strlen($password) < 6) {
        $message = '<div class="message error">La contraseña debe tener al menos 6 caracteres.</div>';
    } else {
        // Verificar si el usuario o email ya existen
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetchColumn() > 0) {
            $message = '<div class="message error">El nombre de usuario o el correo electrónico ya están registrados.</div>';
        } else {
            // Hash de la contraseña
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insertar nuevo usuario
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password])) {
                $message = '<div class="message success">Registro exitoso. Ahora puedes iniciar sesión.</div>';
                // Opcional: Redirigir a la página de login
                // redirect('login.php?registered=true');
            } else {
                $message = '<div class="message error">Hubo un error al registrar el usuario.</div>';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Maniboys</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Registrarse en Maniboys</h1>
        <?php echo $message; ?>
        <form action="register.php" method="POST">
            <label for="username">Nombre de Usuario:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirmar Contraseña:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Registrarse</button>
        </form>
        <div class="links">
            <p>¿Ya tienes una cuenta? <a href="login.php">Iniciar Sesión</a></p>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>