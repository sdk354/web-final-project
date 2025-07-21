document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    const loginForm = document.querySelector('form');

    if (togglePassword && passwordInput && eyeIcon) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            if (type === 'password') {
                eyeIcon.classList.remove('eye-off-icon');
                eyeIcon.classList.add('eye-icon');
            } else {
                eyeIcon.classList.remove('eye-icon');
                eyeIcon.classList.add('eye-off-icon');
            }
        });
    }
});