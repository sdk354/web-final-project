<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF'])); // 'views' or 'auth'

// Define page groups
$guest_pages = ['home_page.php', 'login.php', 'login_user.php', 'login_admin.php'];
$student_pages = ['home_page.php', 'user_page.php'];
$admin_pages = ['home_page.php', 'admin_page.php'];

$is_student = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$is_admin = isset($_SESSION['admin_loggedin']) && $_SESSION['admin_loggedin'] === true;

// Helper function to determine the correct redirection path
function get_redirect_url($target_page) {
    global $current_dir;

    $view_pages = ['home_page.php', 'user_page.php', 'admin_page.php'];
    $target_dir = in_array($target_page, $view_pages) ? 'views' : 'auth';

    if ($current_dir === 'views') {
        return "../views/{$target_page}";
    }
    return $target_page;
}

// --- Redirection Logic ---

// If a logged-in user tries to access a login page, redirect them to their dashboard
if (($is_student || $is_admin) && in_array($current_page, ['login.php', 'login_user.php', 'login_admin.php'])) {
    $redirect_to = $is_student ? 'user_page.php' : 'admin_page.php';
    header("Location: " . get_redirect_url($redirect_to));
    exit;
}

// If a guest tries to access a page they shouldn't, redirect to the login selector
if (!$is_student && !$is_admin && !in_array($current_page, $guest_pages)) {
    header("Location: " . get_redirect_url('login.php'));
    exit;
}

// If a student tries to access an admin page, redirect to the user page
if ($is_student && !in_array($current_page, $student_pages)) {
    header("Location: " . get_redirect_url('user_page.php'));
    exit;
}

// If an admin tries to access a student page, redirect to the admin page
if ($is_admin && !in_array($current_page, $admin_pages)) {
    header("Location: " . get_redirect_url('admin_page.php'));
    exit;
}
?>