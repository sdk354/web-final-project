<?php
$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$user_name = $is_logged_in ? htmlspecialchars($_SESSION['name']) : 'Guest';

$current_page = basename($_SERVER['PHP_SELF']);

if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_start();
    session_unset();
    session_destroy();
    header('Location: login.php');
}

if ($current_page == "admin_page.php") :
?>
    <!-- Sidebar -->
    <!-- Changed background to a solid dark gray and adjusted active link styling for better resemblance -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col shadow-lg overflow-y-auto">
        <div class="p-6 border-b border-gray-700">
            <h1 class="text-xl font-bold flex items-center">
                <i class="fas fa-cube mr-3 text-blue-400"></i> NBSM Management Portal
            </h1>

        </div>
        <nav class="flex-grow mt-6 sidebar-nav">
            <ul>
                <li class="mb-2">
                    <!-- Active link styling: blue text, semi-transparent dark background, blue left border -->
                    <a href="#" class="flex items-center py-3 px-6 text-blue-400 bg-gray-700/50 border-l-4 border-blue-400 font-semibold rounded-r-lg">
                        <i class="fas fa-tachometer-alt mr-4"></i> Dashboard
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" class="flex items-center py-3 px-6 hover:bg-gray-700/50 hover:text-blue-300 transition-colors duration-200">
                        <i class="fas fa-book mr-4"></i> Bookings
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" class="flex items-center py-3 px-6 hover:bg-gray-700/50 hover:text-blue-300 transition-colors duration-200">
                        <i class="fas fa-building mr-4"></i> Facilities
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" class="flex items-center py-3 px-6 hover:bg-gray-700/50 hover:text-blue-300 transition-colors duration-200">
                        <i class="fas fa-user-graduate mr-4"></i> Students
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" class="flex items-center py-3 px-6 hover:bg-gray-700/50 hover:text-blue-300 transition-colors duration-200">
                        <i class="fas fa-chart-bar mr-4"></i> Reports
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" class="flex items-center py-3 px-6 hover:bg-gray-700/50 hover:text-blue-300 transition-colors duration-200">
                        <i class="fas fa-cog mr-4"></i> Settings
                    </a>
                </li>
            </ul>
        </nav>
        <div class="p-6 border-t border-gray-700 mt-auto">
            <div class="flex items-center">
                <img src="https://placehold.co/40x40/007bff/ffffff?text=<?php echo strtoupper(substr($user_name, 0, 1)); ?>" alt="Admin User" class="rounded-full mr-3 border-2 border-blue-400">
                <div>
                    <p class="text-sm font-semibold"><?php echo $user_name; ?></p>
                    <a href="?logout=true" class="text-xs text-gray-400 hover:text-blue-300 transition-colors duration-200">Logout</a>
                </div>
            </div>
        </div>
    </aside>
<?php else: ?>
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col shadow-lg overflow-y-auto">
        <div class="p-6 border-b border-gray-700">
            <h1 class="text-xl font-bold flex items-center">
                <i class="fas fa-cube mr-3 text-blue-400"></i> NBSM Management Portal
            </h1>

        </div>
        <nav class="flex-grow mt-6 sidebar-nav">
            <ul>
                <li class="mb-2">
                    <!-- Active link styling: blue text, semi-transparent dark background, blue left border -->
                    <a href="#" class="flex items-center py-3 px-6 text-blue-400 bg-gray-700/50 border-l-4 border-blue-400 font-semibold rounded-r-lg">
                        <i class="fas fa-tachometer-alt mr-4"></i> Dashboard
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" class="flex items-center py-3 px-6 hover:bg-gray-700/50 hover:text-blue-300 transition-colors duration-200">
                        <i class="fas fa-book mr-4"></i> My Bookings
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" class="flex items-center py-3 px-6 hover:bg-gray-700/50 hover:text-blue-300 transition-colors duration-200">
                        <i class="fas fa-building mr-4"></i> Facilities
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" class="flex items-center py-3 px-6 hover:bg-gray-700/50 hover:text-blue-300 transition-colors duration-200">
                        <i class="fas fa-cog mr-4"></i> Settings
                    </a>
                </li>
            </ul>
        </nav>
        <div class="p-6 border-t border-gray-700 mt-auto">
            <div class="flex items-center">
                <img src="https://placehold.co/40x40/007bff/ffffff?text=<?php echo strtoupper(substr($user_name, 0, 1)); ?>" alt="User" class="rounded-full mr-3 border-2 border-blue-400">
                <div>
                    <p class="text-sm font-semibold"><?php echo $user_name; ?></p>
                    <a href="?logout=true" class="text-xs text-gray-400 hover:text-blue-300 transition-colors duration-200">Logout</a>
                </div>
            </div>
        </div>
    </aside>
<?php endif; ?>