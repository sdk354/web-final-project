<?php
require_once '../core/auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSBM Management Portal</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../../public/js/index.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/index.css">
</head>
<body id="page-login" class="login-main">
<div class="flex flex-col lg:flex-row w-full max-w-5xl bg-white rounded-3xl shadow-2xl">
    <!-- Left Section: Role Selection -->
    <div class="w-full lg:w-1/2 p-8 sm:p-12 md:p-16 flex flex-col justify-center items-center">
        <div class="w-full max-w-md text-center">
            <div class="text-green-600 mb-4 inline-block">
                <span class="building-icon w-12 h-12"></span>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Welcome to the Portal</h2>
            <p class="text-gray-500 text-sm mb-8">How would you like to sign in?</p>

            <div class="space-y-4">
                <a href="login_user.php"
                   class="block w-full py-3 rounded-xl bg-gradient-to-r from-green-500 to-blue-500 text-white font-semibold text-lg shadow-lg hover:from-green-600 hover:to-blue-600 transition duration-300 ease-in-out">
                    Student
                </a>
                <a href="login_admin.php"
                   class="block w-full py-3 rounded-xl bg-gradient-to-r from-gray-700 to-gray-800 text-white font-semibold text-lg shadow-lg hover:from-gray-800 hover:to-gray-900 transition duration-300 ease-in-out">
                    Admin
                </a>
            </div>

            <div class="text-center mt-6">
                <a href="../views/home_page.php" class="text-sm text-gray-600 hover:text-green-500 transition-colors">
                    &larr; Back to Home
                </a>
            </div>
        </div>
    </div>

    <!-- Right Section: Portal Description -->
    <div class="w-full lg:w-1/2 p-8 sm:p-12 md:p-16 bg-blue-700 text-white rounded-r-3xl flex flex-col justify-center items-center text-center background-pattern">
        <div class="max-w-md">
            <div class="mb-6">
                <span class="building-icon w-16 h-16 text-white"></span>
            </div>
            <h2 class="text-4xl font-bold mb-4">NSBM Management Portal</h2>
            <p class="text-blue-100 text-lg mb-8">
                Efficiently manage study halls and sports facilities bookings
            </p>

            <ul class="space-y-6 text-left">
                <li class="flex items-start">
                    <span class="checkmark-icon text-green-300 mt-1 mr-3 flex-shrink-0"></span>
                    <div>
                        <h3 class="text-xl font-semibold mb-1">Easy Booking</h3>
                        <p class="text-blue-200">Book facilities in just a few clicks</p>
                    </div>
                </li>
                <li class="flex items-start">
                    <span class="calendar-icon text-green-300 mt-1 mr-3 flex-shrink-0"></span>
                    <div>
                        <h3 class="text-xl font-semibold mb-1">Real-Time Availability</h3>
                        <p class="text-blue-200">Check facility availability instantly</p>
                    </div>
                </li>
                <li class="flex items-start">
                    <span class="document-icon text-green-300 mt-1 mr-3 flex-shrink-0"></span>
                    <div>
                        <h3 class="text-xl font-semibold mb-1">Manage Bookings</h3>
                        <p class="text-blue-200">View and manage all your bookings</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>