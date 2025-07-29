<?php
require_once '../core/auth_check.php';
$user_name = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/index.css">
</head>
<body class="flex h-screen overflow-hidden">

<?php require_once 'partials/sidebar.php'; ?>

<!-- Main Content Area -->
<div class="flex-1 flex flex-col overflow-hidden">

    <?php require_once 'partials/header.php'; ?>

    <!-- Dashboard Content -->
    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Welcome Back, <?php echo $user_name; ?>!</h2>
        <p class="text-gray-600 mb-6">Here's an overview of your bookings and available facilities.</p>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Active Bookings Card -->
            <div class="bg-white rounded-xl shadow-md p-6 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Active Bookings</p>
                    <h3 class="text-3xl font-bold text-gray-800">3</h3>
                    <p class="text-gray-500 text-sm mt-1">Upcoming bookings this week</p>
                </div>
                <div class="text-gray-400">
                    <i class="fas fa-circle text-xs"></i>
                </div>
            </div>

            <!-- Favorite Facility Card -->
            <div class="bg-white rounded-xl shadow-md p-6 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Favorite Facility</p>
                    <h3 class="text-xl font-bold text-gray-800">Swimming Pool</h3>
                    <p class="text-gray-500 text-sm mt-1">Booked 8 times this semester</p>
                </div>
                <div class="text-yellow-400">
                    <i class="fas fa-star text-2xl"></i>
                </div>
            </div>

            <!-- Next Booking Card -->
            <div class="bg-white rounded-xl shadow-md p-6 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Next Booking</p>
                    <h3 class="text-xl font-bold text-gray-800">Study Hall A</h3>
                    <p class="text-gray-500 text-sm mt-1">Today, 2:00 PM - 4:00 PM</p>
                </div>
                <div class="text-purple-400">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Upcoming Bookings Table -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Upcoming Bookings</h3>
                <a href="#" class="text-blue-500 hover:underline text-sm">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">
                            Facility
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-lg">
                            Status
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center">
                                <i class="fas fa-book-reader text-blue-500 mr-3"></i>
                                <div>
                                    <p>Study Hall A</p>
                                    <p class="text-gray-500 text-xs">Main Building, 3rd Floor</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Today</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2:00 PM - 4:00 PM</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Confirmed</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center">
                                <i class="fas fa-swimmer text-blue-500 mr-3"></i>
                                <div>
                                    <p>Swimming Pool</p>
                                    <p class="text-gray-500 text-xs">Sports Complex</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Tomorrow</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">10:00 AM - 12:00 PM</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Confirmed</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center">
                                <i class="fas fa-table-tennis text-blue-500 mr-3"></i>
                                <div>
                                    <p>Badminton Court</p>
                                    <p class="text-gray-500 text-xs">Sports Complex</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Oct 16, 2023</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4:00 PM - 6:00 PM</td>
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
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Popular Facilities</h3>
                <a href="#" class="text-blue-500 hover:underline text-sm">View All</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Swimming Pool Card -->
                <div class="bg-blue-100 rounded-xl shadow-md p-6 flex flex-col justify-between">
                    <div>
                        <h4 class="text-2xl font-bold text-blue-800 mb-2">Swimming Pool</h4>
                        <p class="text-blue-700 text-sm mb-4">Olympic-sized pool with 8 lanes</p>
                    </div>
                    <button class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-md">
                        Book Now
                    </button>
                </div>

                <!-- Study Hall A Card -->
                <div class="bg-gray-200 rounded-xl shadow-md p-6 flex flex-col justify-between">
                    <div>
                        <h4 class="text-2xl font-bold text-gray-800 mb-2">Study Hall A</h4>
                        <p class="text-gray-700 text-sm mb-4">Quiet study space with 30 seats</p>
                    </div>
                    <button class="bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors duration-200 shadow-md">
                        Book Now
                    </button>
                </div>

                <!-- Badminton Court Card -->
                <div class="bg-red-100 rounded-xl shadow-md p-6 flex flex-col justify-between">
                    <div>
                        <h4 class="text-2xl font-bold text-red-800 mb-2">Badminton Court</h4>
                        <p class="text-red-700 text-sm mb-4">Professional court with equipment</p>
                    </div>
                    <button class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors duration-200 shadow-md">
                        Book Now
                    </button>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>

