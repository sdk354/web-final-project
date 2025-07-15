// JavaScript for password visibility toggle
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    const loginForm = document.querySelector('form');

    if (togglePassword && passwordInput && eyeIcon) {
        togglePassword.addEventListener('click', function() {
            // Toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle the eye icon
            if (type === 'password') {
                eyeIcon.classList.remove('eye-off-icon');
                eyeIcon.classList.add('eye-icon');
            } else {
                eyeIcon.classList.remove('eye-icon');
                eyeIcon.classList.add('eye-off-icon');
            }
        });
    }

    // Redirect on login
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();
            // Optionally, validate credentials here
            window.location.href = 'Web assignment html.html';
        });
    }
});