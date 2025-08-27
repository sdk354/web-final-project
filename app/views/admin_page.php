<?php
// Show all errors in dev
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Auth + DB
require_once __DIR__ . '/../core/auth_check.php';
require_once __DIR__ . '/../core/database/db_connect.php';

// Changing bookings from 'Confirmed' to 'Past' after the present date is passed
$sql = "
  UPDATE bookings
  SET status = 'Past'
  WHERE CONCAT(date, ' ', end_time) < NOW()
    AND status <> 'Past'
";

if ( ! $conn->query($sql) ) {
    error_log("Failed to update past bookings: " . $conn->error);
}

// --- 1) Total Bookings & Month-over-Month Change ---
$now        = new DateTime();
$thisMonth  = $now->format('n');
$lastMonth  = (clone $now)->modify('first day of last month')->format('n');

$stmt            = $conn->query("SELECT COUNT(*) AS cnt FROM bookings");
$totalBookings   = (int)$stmt->fetch_assoc()['cnt'];

$stmt            = $conn->query("
  SELECT COUNT(*) AS cnt
  FROM bookings
  WHERE MONTH(date) = {$thisMonth}
");
$thisMonthCount  = (int)$stmt->fetch_assoc()['cnt'];

$stmt            = $conn->query("
  SELECT COUNT(*) AS cnt
  FROM bookings
  WHERE MONTH(date) = {$lastMonth}
");
$lastMonthCount  = (int)$stmt->fetch_assoc()['cnt'];

if ($lastMonthCount > 0) {
    $bookingDelta     = round((($thisMonthCount - $lastMonthCount) / $lastMonthCount) * 100);
    $bookingDeltaSign = $bookingDelta >= 0 ? 'up' : 'down';
} else {
    $bookingDelta     = 0;
    $bookingDeltaSign = 'up';
}

// --- 2) Active Students ---
$stmt              = $conn->query("SELECT COUNT(*) AS cnt FROM students");
$activeStudents    = (int)$stmt->fetch_assoc()['cnt'];
$studentDelta      = 0;
$studentDeltaSign  = 'up';

// --- 3) Pending Requests ---
$stmt             = $conn->query("SELECT COUNT(*) AS cnt FROM bookings WHERE status = 'Pending'");
$pendingCount     = (int)$stmt->fetch_assoc()['cnt'];

// --- 4) Facility Usage % This Week ---
$totalFacilities   = (int)$conn->query("SELECT COUNT(*) AS cnt FROM facilities")->fetch_assoc()['cnt'];
$weekStart         = (new DateTime('monday this week'))->format('Y-m-d');
$weekEnd           = (new DateTime('sunday this week'))->format('Y-m-d');

$stmt              = $conn->query("
  SELECT COUNT(DISTINCT facility_id) AS cnt
  FROM bookings
  WHERE status = 'Confirmed'
    AND date BETWEEN '{$weekStart}' AND '{$weekEnd}'
");
$usedFacilities     = (int)$stmt->fetch_assoc()['cnt'];
$facilityUsagePct   = $totalFacilities
        ? round(($usedFacilities / $totalFacilities) * 100)
        : 0;

// --- 5) Facility Usage Statistics (for bar chart) ---
$usageStats = [];
$result     = $conn->query("
  SELECT f.name, COUNT(b.booking_id) AS cnt
  FROM facilities f
  LEFT JOIN bookings b
    ON b.facility_id = f.facility_id
   AND b.status = 'Confirmed'
  GROUP BY f.facility_id
  ORDER BY cnt DESC
");
$maxUsage = 0;
while ($row = $result->fetch_assoc()) {
    $cnt            = (int)$row['cnt'];
    $usageStats[]   = ['name' => $row['name'], 'cnt' => $cnt];
    $maxUsage       = max($maxUsage, $cnt);
}

// --- 6) Weekly Booking Trends (last 7 days) ---
$trends   = [];
$maxTrend = 0;
for ($i = 6; $i >= 0; $i--) {
    $day       = (new DateTime("-{$i} days"))->format('Y-m-d');
    $label     = (new DateTime($day))->format('D');
    $countRow  = $conn->query("
      SELECT COUNT(*) AS cnt
      FROM bookings
      WHERE date = '{$day}'
    ")->fetch_assoc();
    $cnt       = (int)$countRow['cnt'];
    $trends[]  = ['day' => $label, 'cnt' => $cnt];
    $maxTrend  = max($maxTrend, $cnt);
}

// --- 7) Recent Bookings ---
$recentBookings = [];
$res             = $conn->query("
  SELECT b.booking_id,
         s.student_id,
         f.name        AS facility_name,
         b.date,
         b.start_time,
         b.end_time,
         b.status
  FROM bookings b
  JOIN students s ON s.student_id = b.student_id
  JOIN facilities f ON f.facility_id = b.facility_id
  ORDER BY b.date DESC, b.start_time DESC
  LIMIT 4
");
while ($r = $res->fetch_assoc()) {
    $recentBookings[] = $r;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <title>Admin Dashboard</title>

    <!-- Tailwind & Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Your CSS -->
    <link rel="stylesheet" href="../../public/css/index.css">

    <!-- FontAwesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="flex h-screen overflow-hidden">
<?php require_once 'partials/sidebar.php'; ?>
<div class="flex-1 flex flex-col overflow-hidden">
    <?php require_once 'partials/header.php'; ?>
    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">

        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Admin Dashboard</h2>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Bookings -->
            <div class="bg-white rounded-xl shadow-md p-6 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Total Bookings</p>
                    <h3 class="text-3xl font-bold text-gray-800"><?= $totalBookings ?></h3>
                    <p class="text-<?= $bookingDeltaSign === 'up' ? 'green' : 'red' ?>-500 text-sm mt-1">
                        <i class="fas fa-arrow-<?= $bookingDeltaSign ?> mr-1"></i>
                        <?= abs($bookingDelta) ?>% Increase
                        <span class="text-gray-400 ml-2">This month</span>
                    </p>
                </div>
                <div class="bg-blue-100 text-blue-500 p-3 rounded-full">
                    <i class="fas fa-clipboard-list text-2xl"></i>
                </div>
            </div>

            <!-- Active Students -->
            <div class="bg-white rounded-xl shadow-md p-6 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Active Students</p>
                    <h3 class="text-3xl font-bold text-gray-800"><?= $activeStudents ?></h3>
                    <p class="text-<?= $studentDeltaSign === 'up' ? 'green' : 'red' ?>-500 text-sm mt-1">
                        <i class="fas fa-arrow-<?= $studentDeltaSign ?> mr-1"></i>
                        <?= $studentDelta ?>% Increase
                        <span class="text-gray-400 ml-2">This semester</span>
                    </p>
                </div>
                <div class="bg-green-100 text-green-500 p-3 rounded-full">
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
            </div>

            <!-- Facility Usage -->
            <div class="bg-white rounded-xl shadow-md p-6 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Facility Usage</p>
                    <h3 class="text-3xl font-bold text-gray-800"><?= $facilityUsagePct ?>%</h3>
                    <p class="text-green-500 text-sm mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <?= $usedFacilities ?> of <?= $totalFacilities ?> used
                        <span class="text-gray-400 ml-2">This week</span>
                    </p>
                </div>
                <div class="bg-purple-100 text-purple-500 p-3 rounded-full">
                    <i class="fas fa-chart-pie text-2xl"></i>
                </div>
            </div>

            <!-- Pending Requests -->
            <div class="bg-white rounded-xl shadow-md p-6 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Pending Requests</p>
                    <h3 class="text-3xl font-bold text-gray-800"><?= $pendingCount ?></h3>
                    <p class="text-red-500 text-sm mt-1">
                        <i class="fas fa-arrow-down mr-1"></i>
                        New today
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
            <!-- Facility Usage Statistics -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Facility Usage Statistics
                </h3>
                <div class="h-64">
                    <canvas id="usageChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- Weekly Booking Trends -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Weekly Booking Trends
                </h3>
                <div class="h-64">
                    <canvas id="trendChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Bookings Table -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Bookings</h3>
                <button
                        id="toggleAdminBookingsBtn"
                        class="text-blue-500 hover:underline text-sm focus:outline-none"
                >View All</button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Student ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Facility
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Time
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($recentBookings)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-sm text-gray-500">
                                No recent bookings
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentBookings as $b):
                            switch ($b['status']) {
                                case 'Confirmed': $badge = ['bg-green-100','text-green-800']; break;
                                case 'Cancelled': $badge = ['bg-red-100','text-red-800'];   break;
                                default:          $badge = ['bg-yellow-100','text-yellow-800']; break;
                            }
                            ?>
                            <tr class="admin-booking-row">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= (int)$b['student_id'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($b['facility_name']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= date('M j, Y', strtotime($b['date'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= date('g:i A', strtotime($b['start_time'])) ?> –
                                    <?= date('g:i A', strtotime($b['end_time'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $badge[0] ?> <?= $badge[1] ?>">
                      <?= htmlspecialchars($b['status']) ?>
                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button
                                            type="button"
                                            class="booking-edit-btn text-blue-600 hover:text-blue-900 mr-3"
                                            data-id="<?= $b['booking_id'] ?>"
                                            data-date="<?= $b['date'] ?>"
                                            data-start="<?= $b['start_time'] ?>"
                                            data-end="<?= $b['end_time'] ?>"
                                            data-status="<?= $b['status'] ?>"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form
                                            method="post"
                                            action="../core/delete_booking.php"
                                            class="inline"
                                            onsubmit="return confirm('Are you sure?');"
                                    >
                                        <input type="hidden" name="booking_id" value="<?= $b['booking_id'] ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<!-- Edit Booking Modal -->
<?php include 'partials/admin_booking_modal.php';?>

<script>
	window.adminChartData = {
		usageLabels: <?= json_encode(array_column($usageStats, 'name')) ?>,
		usageCounts: <?= json_encode(array_column($usageStats, 'cnt')) ?>,
		trendLabels: <?= json_encode(array_column($trends, 'day')) ?>,
		trendCounts: <?= json_encode(array_column($trends, 'cnt')) ?>
	};
</script>
<!-- Your global JS -->
<script src="../../public/js/index.js"></script>
<script src="../../public/js/admin_charts.js"></script>

</body>
</html>
