<?php
// Redirect if accessed directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    session_start();
    $is_student = !empty($_SESSION['loggedin']);
    $is_admin   = !empty($_SESSION['admin_loggedin']);
    if ($is_admin)       header("Location: ../admin_page.php");
    elseif ($is_student) header("Location: ../user_page.php");
    else                 header("Location: ../login.php");
    exit;
}

// Start session once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle logout
if (!empty($_GET['logout']) && $_GET['logout'] === 'true') {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

// Context
$current_page  = basename($_SERVER['PHP_SELF']);
$is_logged_in  = !empty($_SESSION['loggedin']) || !empty($_SESSION['admin_loggedin']);
$user_role     = $_SESSION['role'] ?? null;
$user_name     = $_SESSION['name'] ?? 'Guest';
$initial       = strtoupper(substr($user_name, 0, 1));
?>

<header class="bg-white shadow-md py-4 px-6 flex items-center justify-between z-10">
    <!-- Left: Branding -->
    <div class="flex items-center">
        <i class="fas fa-cube mr-3 text-blue-500 text-2xl"></i>
        <h1 class="text-xl font-bold">NSBM Management Portal</h1>
    </div>

    <!-- Right: Links + (Avatar + Logout if logged in) -->
    <div class="flex items-center space-x-6">
        <a href="../views/home_page.php" class="text-sm text-gray-700 hover:text-blue-500">Home</a>
        <?php if (!$is_logged_in): ?>
            <a href="../views/login.php" class="text-sm text-gray-700 hover:text-blue-500">Login</a>
        <?php elseif ($user_role === 'user'): ?>
            <a href="../views/user_page.php" class="text-sm text-gray-700 hover:text-blue-500">User</a>
        <?php elseif ($user_role === 'admin'): ?>
            <a href="../views/admin_page.php" class="text-sm font-semibold text-gray-700 hover:text-blue-500">Admin</a>
        <?php endif; ?>

        <?php if ($is_logged_in): ?>
            <!-- Avatar -->
            <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                <?= $initial; ?>
            </div>
            <!-- Logout -->
            <a
                    href="?logout=true"
                    class="text-gray-600 hover:text-red-500 transition-colors duration-200"
                    title="Logout"
            >
                <i class="fas fa-sign-out-alt text-lg"></i>
            </a>
        <?php endif; ?>
    </div>
</header>
