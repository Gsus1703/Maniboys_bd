// public/js/script.js

document.addEventListener('DOMContentLoaded', () => {
    // Ejemplo de validación básica de formulario de registro
    const registerForm = document.querySelector('form[action="register.php"]');
    if (registerForm) {
        registerForm.addEventListener('submit', (event) => {
            const password = registerForm.querySelector('input[name="password"]').value;
            const confirmPassword = registerForm.querySelector('input[name="confirm_password"]').value;
            const username = registerForm.querySelector('input[name="username"]').value;

            if (username.length < 3) {
                alert('El nombre de usuario debe tener al menos 3 caracteres.');
                event.preventDefault();
                return;
            }

            if (password.length < 6) {
                alert('La contraseña debe tener al menos 6 caracteres.');
                event.preventDefault();
                return;
            }
            if (password !== confirmPassword) {
                alert('Las contraseñas no coinciden.');
                event.preventDefault();
            }
        });
    }

    // Ejemplo de confirmación al eliminar
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            if (!confirm('¿Estás seguro de que quieres eliminar este elemento?')) {
                event.preventDefault();
            }
        });
    });
});