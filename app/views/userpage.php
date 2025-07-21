<?php
session_start();

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSBM Student Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../../public/js/userpage.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/userpage.css">
</head>
<body id="page-userpage">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-gray-100 flex flex-col sidebar-pattern flex-shrink-0">
        <div class="p-6 border-b border-gray-700 flex items-center">
            <span class="nsbm-icon text-green-400 mr-3"></span>
            <span class="text-xl font-semibold">NSBM Bookings</span>
        </div>
        <nav class="flex-grow p-4 space-y-2 overflow-y-auto sidebar">
            <a href="#" class="flex items-center px-4 py-3 rounded-xl text-white bg-blue-600 font-medium shadow-md">
                <span class="dashboard-icon mr-3"></span>
                Dashboard
            </a>
            <a href="#" class="flex items-center px-4 py-3 rounded-xl hover:bg-gray-700 transition duration-200">
                <span class="bookings-icon mr-3"></span>
                My Bookings
            </a>
            <a href="#" class="flex items-center px-4 py-3 rounded-xl hover:bg-gray-700 transition duration-200">
                <span class="profile-icon mr-3"></span>
                Profile
            </a>
            <div class="border-t border-gray-700 my-4"></div>
            <a href="#" class="flex items-center px-4 py-3 rounded-xl hover:bg-gray-700 transition duration-200">
                <span class="help-icon mr-3"></span>
                Help & Support
            </a>
        </nav>
        <div class="p-6 border-t border-gray-700">
            <div class="flex items-center mb-2">
                <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center text-sm font-bold">KD</div>
                <div class="ml-3">
                    <p class="text-sm font-medium">Username</p>
                    <p class="text-xs text-gray-400">Student</p>
                </div>
            </div>
            <a href="?logout=true" class="flex items-center text-sm text-gray-400 hover:text-white transition duration-200">
                <span class="logout-icon mr-2"></span>
                Logout
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm p-4 flex justify-between items-center z-10">
            <h1 class="text-2xl font-semibold text-gray-800">Student Dashboard</h1>
            <div class="flex items-center space-x-4">
                <a href="homepage.html" class="text-gray-600 hover:text-gray-900 transition duration-200">Home</a>
                <a href="userpage.php" class="text-gray-600 hover:text-gray-900 transition duration-200">User</a>
                <a href="#" class="text-gray-600 hover:text-gray-900 transition duration-200">Admin</a>
                <a href="login.html" class="text-gray-600 hover:text-gray-900 transition duration-200 flex items-center">
                    <span class="login-icon mr-1"></span>
                    Login
                </a>
                <button class="relative text-gray-600 hover:text-gray-900 transition duration-200">
                    <span class="bell-icon"></span>
                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full ring-2 ring-white bg-red-500"></span>
                </button>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="flex-1 p-6 overflow-y-auto">
            <h2 class="text-xl font-semibold text-gray-700 mb-6">Welcome back, Username!</h2>
            <p class="text-gray-500 mb-8">Here's an overview of your bookings and available facilities.</p>

            <!-- Top Cards Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Active Bookings Card -->
                <div class="bg-white rounded-xl shadow-md p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-700">Active Bookings</h3>
                            <span class="info-icon text-gray-400"></span>
                        </div>
                        <p class="text-5xl font-bold text-gray-900 mb-2">3</p>
                        <p class="text-gray-500 text-sm">Upcoming bookings this week</p>
                    </div>
                </div>

                <!-- Favorite Facility Card -->
                <div class="bg-white rounded-xl shadow-md p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-700">Favorite Facility</h3>
                            <span class="star-icon text-yellow-500"></span>
                        </div>
                        <p class="text-2xl font-semibold text-gray-900 mb-1">Swimming Pool</p>
                        <p class="text-gray-500 text-sm">Booked 8 times this semester</p>
                    </div>
                </div>

                <!-- Next Booking Card -->
                <div class="bg-white rounded-xl shadow-md p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-700">Next Booking</h3>
                            <span class="clock-icon text-purple-500"></span>
                        </div>
                        <p class="text-2xl font-semibold text-gray-900 mb-1">Study Hall A</p>
                        <p class="text-gray-500 text-sm">Today, 2:00 PM - 4:00 PM</p>
                    </div>
                </div>
            </div>

            <!-- Upcoming Bookings Section -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-700">Upcoming Bookings</h3>
                    <a href="#" class="text-blue-600 hover:underline text-sm">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facility</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="study-hall-icon text-blue-500 mr-2"></span>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Study Hall A</div>
                                            <div class="text-sm text-gray-500">Main Building, 3rd Floor</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Today</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">2:00 PM - 4:00 PM</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Confirmed</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="swimming-pool-icon text-teal-500 mr-2"></span>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Swimming Pool</div>
                                            <div class="text-sm text-gray-500">Sports Complex</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Tomorrow</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">10:00 AM - 12:00 PM</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Confirmed</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="badminton-icon text-red-500 mr-2"></span>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Badminton Court</div>
                                            <div class="text-sm text-gray-500">Sports Complex</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Oct 18, 2023</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">4:00 PM - 6:00 PM</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Popular Facilities Section -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-700">Popular Facilities</h3>
                    <a href="#" class="text-blue-600 hover:underline text-sm">View All</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Swimming Pool Card -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <img src="https://placehold.co/400x200/ADD8E6/000000?text=Swimming+Pool" alt="Swimming Pool" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h4 class="text-lg font-semibold text-gray-800 mb-1">Swimming Pool</h4>
                            <p class="text-gray-600 text-sm mb-3">Olympic-sized pool with 8 lanes</p>
                            <button class="w-full py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition duration-200">
                                Book Now
                            </button>
                        </div>
                    </div>
                    <!-- Study Hall A Card -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <img src="https://placehold.co/400x200/D3D3D3/000000?text=Study+Hall+A" alt="Study Hall A" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h4 class="text-lg font-semibold text-gray-800 mb-1">Study Hall A</h4>
                            <p class="text-gray-600 text-sm mb-3">Quiet study space with 30 seats</p>
                            <button class="w-full py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition duration-200">
                                Book Now
                            </button>
                        </div>
                    </div>
                    <!-- Badminton Court Card -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <img src="https://placehold.co/400x200/F08080/000000?text=Badminton+Court" alt="Badminton Court" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h4 class="text-lg font-semibold text-gray-800 mb-1">Badminton Court</h4>
                            <p class="text-gray-600 text-sm mb-3">Professional court with equipment</p>
                            <button class="w-full py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition duration-200">
                                Book Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
