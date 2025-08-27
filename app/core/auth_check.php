<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF'])); // 'views' or 'auth'

// Detect AJAX
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Define page groups
$guest_pages   = ['home_page.php', 'login.php', 'login_user.php', 'login_admin.php'];
$student_pages = ['home_page.php', 'user_page.php', 'book_facility.php', 'cancel_booking.php'];
$admin_pages   = ['home_page.php', 'admin_page.php', 'edit_booking.php', 'delete_booking.php'];

$is_student = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$is_admin   = isset($_SESSION['admin_loggedin']) && $_SESSION['admin_loggedin'] === true;

// Helper function to determine the correct redirection path
function get_redirect_url($target_page) {
    $view_pages = ['home_page.php', 'user_page.php', 'admin_page.php'];
    if (in_array($target_page, $view_pages)) {
        return "../views/{$target_page}";
    }
    return $target_page;
}

// --- Redirection / Deny Logic ---
function deny_access($target_page, $msg, $isAjax) {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $msg]);
    } else {
        header("Location: " . get_redirect_url($target_page));
    }
    exit;
}

// If a logged-in user tries to access a login page
if (($is_student || $is_admin) && in_array($current_page, ['login.php', 'login_user.php', 'login_admin.php'])) {
    $redirect_to = $is_student ? 'user_page.php' : 'admin_page.php';
    deny_access($redirect_to, 'Already logged in', $isAjax);
}

// If a guest tries to access a restricted page
if (!$is_student && !$is_admin && !in_array($current_page, $guest_pages)) {
    deny_access('login.php', 'Not authenticated', $isAjax);
}

// Student trying to access an admin page
if ($is_student && !in_array($current_page, $student_pages)) {
    deny_access('user_page.php', 'Not authorized for this page', $isAjax);
}

// Admin trying to access a student page
if ($is_admin && !in_array($current_page, $admin_pages)) {
    deny_access('admin_page.php', 'Not authorized for this page', $isAjax);
}
?>
