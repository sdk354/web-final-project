<?php
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    // Accessed directly, apply your redirect logic
    session_start();
    $is_student = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
    $is_admin = isset($_SESSION['admin_loggedin']) && $_SESSION['admin_loggedin'] === true;

    if ($is_admin) {
        header("Location: ../admin_page.php");
        exit;
    } elseif ($is_student) {
        header("Location: ../user_page.php");
        exit;
    } else {
        header("Location: ../login.php");
        exit;
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
$is_logged_in = (
    (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) ||
    (isset($_SESSION['admin_loggedin']) && $_SESSION['admin_loggedin'] === true)
);
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

if ($current_page == "home_page.php") :
?>
    <header class="bg-white text-gray-800 shadow-md py-4 px-6 flex items-center justify-between z-10">
        <div class="flex items-center">
            <i class="fas fa-cube mr-3 text-blue-500 text-2xl"></i>
            <h1 class="text-xl font-bold">NSBM Management Portal</h1>
        </div>
        <div class="flex items-center space-x-6">
            <a href="#" class="text-gray-600 hover:text-blue-500 transition-colors duration-200">
                <i class="fas fa-bell text-xl"></i>
            </a>
            <div class="flex items-center space-x-2 text-gray-700">
                <a href="../views/home_page.php" class="text-sm">Home</a>
                <?php if (!$is_logged_in): ?>
                    <span class="text-gray-400">|</span>
                    <a href="../views/login.php" class="text-sm">Login</a>
                <?php elseif ($user_role === 'user'): ?>
                    <span class="text-gray-400">|</span>
                    <a href="../views/user_page.php" class="text-sm">User</a>
                <?php elseif ($user_role === 'admin'): ?>
                    <span class="text-gray-400">|</span>
                    <a href="../views/admin_page.php" class="text-sm font-semibold">Admin</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
<?php else: ?>
    <header class="bg-white shadow-md py-4 px-6 flex items-center justify-between z-10">
        <div class="relative w-full max-w-md">
            <input type="text" placeholder="Search..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
        <div class="flex items-center space-x-2 text-gray-700">
            <a href="../views/home_page.php" class="text-sm">Home</a>
            <?php if (!$is_logged_in): ?>
                <span class="text-gray-400">|</span>
                <a href="../views/login.php" class="text-sm">Login</a>
            <?php elseif ($user_role === 'user'): ?>
                <span class="text-gray-400">|</span>
                <a href="../views/user_page.php" class="text-sm">User</a>
            <?php elseif ($user_role === 'admin'): ?>
                <span class="text-gray-400">|</span>
                <a href="../views/admin_page.php" class="text-sm font-semibold">Admin</a>
            <?php endif; ?>
            <!--<a href="#" class="text-gray-600 hover:text-blue-500 transition-colors duration-200 ml-2">
                <i class="fas fa-user-circle text-2xl"></i>
            </a>
            <a href="#" class="text-gray-600 hover:text-blue-500 transition-colors duration-200">
                <i class="fas fa-caret-down text-lg"></i>
            </a>-->
        </div>
    </header>
<?php endif; ?>