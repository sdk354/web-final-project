<?php
require_once '../core/auth_check.php';

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: home_page.php");
    exit();
}

$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSBM Management Portal</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/index.css">
</head>
<body class="flex flex-col min-h-screen">

<?php require_once 'partials/header.php'; ?>

<!-- Hero Section - Added a gradient background -->
<section class="bg-gradient-to-r from-gray-900 to-gray-700 text-white py-16 px-6 text-center">
    <h2 class="text-4xl font-bold mb-4">Welcome to NSBM Management Portal</h2>
    <p class="text-lg mb-8">Book study halls and sports facilities with ease. Check availability and reserve your spot
        in just a few clicks.</p>
    <div class="flex justify-center space-x-4">
        <?php if ($is_logged_in): ?>
            <a href="user_page.php" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition-transform transform hover:scale-105 duration-300">
                Go to Dashboard
            </a>
            <a href="?logout=true" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition-transform transform hover:scale-105 duration-300">
                Logout
            </a>
        <?php else: ?>
            <a href="login.php" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition-transform transform hover:scale-105 duration-300">
                Login
            </a>
        <?php endif; ?>
    </div>
</section>




<!-- Main Content Area -->
<main class="flex-1 p-6"> <!-- Removed bg-gray-100 here to let body gradient show -->
    <!-- Tabs for Study Halls / Sports Facilities -->
    <div class="flex justify-center border-b border-gray-300 mb-8">
        <button id="studyHallsTab"
                class="py-3 px-6 text-gray-700 hover:text-blue-500 transition-colors duration-200 tab-active">Study
            Halls
        </button>
        <button id="sportsFacilitiesTab"
                class="py-3 px-6 text-gray-700 hover:text-blue-500 transition-colors duration-200">Sports Facilities
        </button>
    </div>

    <!-- Content Sections for Tabs -->
    <div id="studyHallsContent">
        <!-- Available Study Halls Section -->
        <section class="max-w-6xl mx-auto mb-12 bg-white rounded-xl shadow-md p-6">
            <!-- Added bg-white to content sections -->
            <h3 class="text-2xl font-semibold text-gray-800 mb-2">Available Study Halls</h3>
            <p class="text-gray-600 mb-8">Book a quiet space for your individual or group study sessions.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Study Hall A Card -->
                <div class="bg-green-50 rounded-xl shadow-md p-6 flex flex-col items-center text-center border border-green-200">
                    <i class="fas fa-book-open text-green-600 text-5xl mb-4"></i>
                    <h4 class="text-xl font-bold text-green-800 mb-2">Study Hall A</h4>
                    <p class="text-green-700 text-sm mb-2"><i class="fas fa-users mr-2"></i>Capacity: 30 students</p>
                    <p class="text-green-600 text-sm mb-4"><i class="fas fa-check-circle mr-2"></i>Available Now</p>
                    <button class="bg-green-600 text-white py-2 px-6 rounded-lg hover:bg-green-700 transition-colors duration-200 shadow-md">
                        Book Now
                    </button>
                </div>

                <!-- Study Hall B Card -->
                <div class="bg-green-50 rounded-xl shadow-md p-6 flex flex-col items-center text-center border border-green-200">
                    <i class="fas fa-building text-green-600 text-5xl mb-4"></i>
                    <h4 class="text-xl font-bold text-green-800 mb-2">Study Hall B</h4>
                    <p class="text-green-700 text-sm mb-2"><i class="fas fa-users mr-2"></i>Capacity: 20 students</p>
                    <p class="text-green-600 text-sm mb-4"><i class="fas fa-check-circle mr-2"></i>Available Now</p>
                    <button class="bg-green-600 text-white py-2 px-6 rounded-lg hover:bg-green-700 transition-colors duration-200 shadow-md">
                        Book Now
                    </button>
                </div>

                <!-- Group Study Room Card -->
                <div class="bg-red-50 rounded-xl shadow-md p-6 flex flex-col items-center text-center border border-red-200">
                    <i class="fas fa-home text-red-600 text-5xl mb-4"></i>
                    <h4 class="text-xl font-bold text-red-800 mb-2">Group Study Room</h4>
                    <p class="text-red-700 text-sm mb-2"><i class="fas fa-users mr-2"></i>Capacity: 10 students</p>
                    <p class="text-red-600 text-sm mb-4"><i class="fas fa-times-circle mr-2"></i>Booked until 3:00 PM
                    </p>
                    <button class="bg-gray-400 text-white py-2 px-6 rounded-lg cursor-not-allowed shadow-md" disabled>
                        Currently Unavailable
                    </button>
                </div>
            </div>
        </section>
    </div>

    <div id="sportsFacilitiesContent" class="hidden">
        <!-- Sports Facilities Section -->
        <section class="max-w-6xl mx-auto mb-12 bg-white rounded-xl shadow-md p-6">
            <!-- Added bg-white to content sections -->
            <h3 class="text-2xl font-semibold text-gray-800 mb-2">Sports Facilities</h3>
            <p class="text-gray-600 mb-8">Book sports facilities for your recreational activities.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Badminton Court Card -->
                <div class="bg-blue-50 rounded-xl shadow-md p-6 flex flex-col items-center text-center border border-blue-200">
                    <i class="fas fa-table-tennis text-blue-600 text-5xl mb-4"></i>
                    <h4 class="text-xl font-bold text-blue-800 mb-2">Badminton Court</h4>
                    <p class="text-blue-700 text-sm mb-2"><i class="fas fa-clock mr-2"></i>Open: 8:00 AM - 8:00 PM</p>
                    <p class="text-blue-600 text-sm mb-4"><i class="fas fa-check-circle mr-2"></i>2 Courts Available</p>
                    <button class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-md">
                        Book Now
                    </button>
                </div>

                <!-- Swimming Pool Card -->
                <div class="bg-blue-50 rounded-xl shadow-md p-6 flex flex-col items-center text-center border border-blue-200">
                    <i class="fas fa-swimmer text-blue-600 text-5xl mb-4"></i>
                    <h4 class="text-xl font-bold text-blue-800 mb-2">Swimming Pool</h4>
                    <p class="text-blue-700 text-sm mb-2"><i class="fas fa-clock mr-2"></i>Open: 6:00 AM - 9:00 PM</p>
                    <p class="text-blue-600 text-sm mb-4"><i class="fas fa-check-circle mr-2"></i>Available Now</p>
                    <button class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-md">
                        Book Now
                    </button>
                </div>

                <!-- Gym Card -->
                <div class="bg-blue-50 rounded-xl shadow-md p-6 flex flex-col items-center text-center border border-blue-200">
                    <i class="fas fa-dumbbell text-blue-600 text-5xl mb-4"></i>
                    <h4 class="text-xl font-bold text-blue-800 mb-2">Gym</h4>
                    <p class="text-blue-700 text-sm mb-2"><i class="fas fa-clock mr-2"></i>Open: 9:00 AM - 10:00 PM</p>
                    <p class="text-blue-600 text-sm mb-4"><i class="fas fa-check-circle mr-2"></i>Available Now</p>
                    <button class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-md">
                        Book Now
                    </button>
                </div>

                <!-- Table Tennis Card -->
                <div class="bg-blue-50 rounded-xl shadow-md p-6 flex flex-col items-center text-center border border-blue-200">
                    <i class="fas fa-table-tennis text-blue-600 text-5xl mb-4"></i>
                    <h4 class="text-xl font-bold text-blue-800 mb-2">Table Tennis</h4>
                    <p class="text-blue-700 text-sm mb-2"><i class="fas fa-clock mr-2"></i>Open: 9:00 AM - 8:00 PM</p>
                    <p class="text-red-600 text-sm mb-4"><i class="fas fa-times-circle mr-2"></i>Fully Booked Today</p>
                    <button class="bg-gray-400 text-white py-2 px-6 rounded-lg cursor-not-allowed shadow-md" disabled>
                        Currently Unavailable
                    </button>
                </div>
            </div>
        </section>
    </div>
</main>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-12 px-6">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Column 1: NSBM Management Portal Info -->
        <div>
            <h4 class="text-xl font-bold mb-4">NSBM Management Portal</h4>
            <p class="text-gray-400 text-sm">Simplifying facility booking for NSBM students.</p>
        </div>

        <!-- Column 2: Quick Links -->
        <div>
            <h4 class="text-xl font-bold mb-4">Quick Links</h4>
            <ul>
                <li class="mb-2"><a href="#"
                                    class="text-gray-400 hover:text-blue-400 transition-colors duration-200 text-sm">Home</a>
                </li>
                <li class="mb-2"><a href="#"
                                    class="text-gray-400 hover:text-blue-400 transition-colors duration-200 text-sm">About</a>
                </li>
                <li class="mb-2"><a href="#"
                                    class="text-gray-400 hover:text-blue-400 transition-colors duration-200 text-sm">Contact</a>
                </li>
                <li class="mb-2"><a href="#"
                                    class="text-gray-400 hover:text-blue-400 transition-colors duration-200 text-sm">Help
                        & Support</a></li>
            </ul>
        </div>

        <!-- Column 3: Contact -->
        <div>
            <h4 class="text-xl font-bold mb-4">Contact</h4>
            <p class="text-gray-400 text-sm mb-2"><i class="fas fa-envelope mr-2"></i>Info@nsbm.edu.lk</p>
            <p class="text-gray-400 text-sm mb-2"><i class="fas fa-phone mr-2"></i>+94 11 123 4567</p>
            <p class="text-gray-400 text-sm"><i class="fas fa-map-marker-alt mr-2"></i>NSBM Green University, Homagama
            </p>
        </div>
    </div>
    <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-500 text-xs">
        &copy; 2023 NSBM Management Portal. All rights reserved.
    </div>
</footer>

<script src="../../public/js/index.js"></script>
</body>
</html>
