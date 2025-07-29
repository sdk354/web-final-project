<?php
require_once '../core/auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NBSM Admin Dashboard</title>
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
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Admin Dashboard</h2>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Bookings Card -->
                <div class="bg-white rounded-xl shadow-md p-6 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Total Bookings</p>
                        <h3 class="text-3xl font-bold text-gray-800">248</h3>
                        <p class="text-green-500 text-sm mt-1">
                            <i class="fas fa-arrow-up mr-1"></i> 10% Increase
                            <span class="text-gray-400 ml-2">This month</span>
                        </p>
                    </div>
                    <div class="bg-blue-100 text-blue-500 p-3 rounded-full">
                        <i class="fas fa-clipboard-list text-2xl"></i>
                    </div>
                </div>

                <!-- Active Students Card -->
                <div class="bg-white rounded-xl shadow-md p-6 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Active Students</p>
                        <h3 class="text-3xl font-bold text-gray-800">1,423</h3>
                        <p class="text-green-500 text-sm mt-1">
                            <i class="fas fa-arrow-up mr-1"></i> 5% Increase
                            <span class="text-gray-400 ml-2">This semester</span>
                        </p>
                    </div>
                    <div class="bg-green-100 text-green-500 p-3 rounded-full">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                </div>

                <!-- Facility Usage Card -->
                <div class="bg-white rounded-xl shadow-md p-6 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Facility Usage</p>
                        <h3 class="text-3xl font-bold text-gray-800">78%</h3>
                        <p class="text-green-500 text-sm mt-1">
                            <i class="fas fa-arrow-up mr-1"></i> 8% Increase
                            <span class="text-gray-400 ml-2">Average</span>
                        </p>
                    </div>
                    <div class="bg-purple-100 text-purple-500 p-3 rounded-full">
                        <i class="fas fa-chart-pie text-2xl"></i>
                    </div>
                </div>

                <!-- Pending Requests Card -->
                <div class="bg-white rounded-xl shadow-md p-6 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Pending Requests</p>
                        <h3 class="text-3xl font-bold text-gray-800">12</h3>
                        <p class="text-red-500 text-sm mt-1">
                            <i class="fas fa-arrow-down mr-1"></i> 2 new today
                            <span class="text-gray-400 ml-2">Need attention</span>
                        </p>
                    </div>
                    <div class="bg-yellow-100 text-yellow-500 p-3 rounded-full">
                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Facility Usage Statistics Chart -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Facility Usage Statistics</h3>
                    <div class="flex items-end h-64 space-x-4">
                        <!-- Bars (adjust height for visual representation) -->
                        <div class="flex-1 bg-blue-500 rounded-t-lg" style="height: 60%;"></div>
                        <div class="flex-1 bg-blue-500 rounded-t-lg" style="height: 45%;"></div>
                        <div class="flex-1 bg-blue-500 rounded-t-lg" style="height: 80%;"></div>
                        <div class="flex-1 bg-blue-500 rounded-t-lg" style="height: 90%;"></div>
                        <div class="flex-1 bg-blue-500 rounded-t-lg" style="height: 55%;"></div>
                        <div class="flex-1 bg-blue-500 rounded-t-lg" style="height: 70%;"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-2">
                        <span>Study Hall A</span>
                        <span>Study Hall B</span>
                        <span>Badminton</span>
                        <span>Swimming</span>
                        <span>Gym</span>
                        <span>Table Tennis</span>
                    </div>
                </div>

                <!-- Weekly Booking Trends Chart -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Weekly Booking Trends</h3>
                    <div class="flex items-end h-64 space-x-4">
                        <!-- Bars (adjust height for visual representation) -->
                        <div class="flex-1 bg-blue-500 rounded-t-lg" style="height: 40%;"></div>
                        <div class="flex-1 bg-blue-500 rounded-t-lg" style="height: 65%;"></div>
                        <div class="flex-1 bg-blue-500 rounded-t-lg" style="height: 80%;"></div>
                        <div class="flex-1 bg-blue-500 rounded-t-lg" style="height: 95%;"></div>
                        <div class="flex-1 bg-blue-500 rounded-t-lg" style="height: 50%;"></div>
                        <div class="flex-1 bg-blue-500 rounded-t-lg" style="height: 30%;"></div>
                        <div class="flex-1 bg-blue-500 rounded-t-lg" style="height: 70%;"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-2">
                        <span>Mon</span>
                        <span>Tue</span>
                        <span>Wed</span>
                        <span>Thu</span>
                        <span>Fri</span>
                        <span>Sat</span>
                        <span>Sun</span>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings Table -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Recent Bookings</h3>
                    <a href="#" class="text-blue-500 hover:underline text-sm">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">Student ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facility</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-lg">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1723245</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Study Hall A</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Oct 15, 2023</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">10:00 AM - 12:00 PM</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Confirmed</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="#" class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">5167890</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Swimming Pool</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Oct 15, 2023</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2:00 PM - 4:00 PM</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="#" class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3154021</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Badminton Court</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Oct 16, 2023</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3:00 PM - 5:00 PM</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Confirmed</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="#" class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">8987655</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Gym</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Oct 16, 2023</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">8:00 AM - 9:00 AM</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="#" class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
