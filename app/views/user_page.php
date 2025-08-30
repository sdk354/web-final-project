<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../core/auth_check.php';
require_once __DIR__ . '/../core/database/db_connect.php';
require_once __DIR__ . '/../core/controllers/BookingController.php';

$openBookingModal = false;
$pendingFacility  = null;

// 1) did they click “Book Now” while already logged in?
if (isset($_GET['modal'], $_GET['facility']) && $_GET['modal'] === '1') {
    $openBookingModal = true;
    $pendingFacility  = (int)$_GET['facility'];
}
// 2) did they come via login_user.php “pendingBookingFacility”?
else if (isset($_SESSION['pendingBookingFacility'])) {
    $openBookingModal = true;
    $pendingFacility  = (int)$_SESSION['pendingBookingFacility'];
    unset($_SESSION['pendingBookingFacility']);
}

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

$bookingController = new BookingController($conn);

// === Determine limit for Upcoming Bookings table ===
$limit = isset($_GET['all_bookings']) ? null : 3;

// Base query: include *all* statuses so Hide Past works, order newest first
$sqlBookings = "
    SELECT
        b.booking_id,
        b.student_id,
        b.facility_id,
        f.name AS facility_name,
        b.date,
        b.start_time,
        b.end_time,
        b.status
    FROM bookings b
    JOIN facilities f
      ON f.facility_id = b.facility_id
    WHERE b.student_id = ?
    ORDER BY b.date DESC, b.start_time DESC
";

// Add limit if not viewing all
if ($limit) {
    $sqlBookings .= " LIMIT " . (int)$limit;
}

$stmtBookings = $conn->prepare($sqlBookings);
$studentId = (int) $_SESSION['student_id'];
$stmtBookings->bind_param("i", $studentId);
$stmtBookings->execute();
$bookings = $stmtBookings->get_result()->fetch_all(MYSQLI_ASSOC);

$user_name = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'User';

// Fetch all facilities from DB (for modal dropdowns / JS)
$sql = "SELECT facility_id AS id, name FROM facilities ORDER BY name";
$result = $conn->query($sql);
$availableFacilities = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $availableFacilities[] = $row;
    }
}

/* ===== Facility style mapping for Popular Facilities section ===== */
$facilityStyles = [
        1 => ['bg-blue-100',   'text-blue-800',   'text-blue-700',   'bg-blue-600',   'bg-blue-700'],
        2 => ['bg-green-100',  'text-green-800',  'text-green-700',  'bg-green-600',  'bg-green-700'],
        3 => ['bg-yellow-100', 'text-yellow-800', 'text-yellow-700', 'bg-yellow-600', 'bg-yellow-700'],
        4 => ['bg-red-100',    'text-red-800',    'text-red-700',    'bg-red-600',    'bg-red-700'],
        5 => ['bg-indigo-100', 'text-indigo-800', 'text-indigo-700', 'bg-indigo-600', 'bg-indigo-700'],
        6 => ['bg-purple-100', 'text-purple-800', 'text-purple-700', 'bg-purple-600', 'bg-purple-700'],
        7 => ['bg-pink-100',   'text-pink-800',   'text-pink-700',   'bg-pink-600',   'bg-pink-700'],
];

// Top 3 most popular facilities
$sqlTop3 = "
    SELECT f.facility_id AS id,
           f.name,
           f.description,
           COUNT(b.booking_id) AS popularity
    FROM facilities f
    LEFT JOIN bookings b
        ON b.facility_id = f.facility_id
       AND b.status = 'Confirmed'
    GROUP BY f.facility_id
    ORDER BY popularity DESC
    LIMIT 3
";

$popularFacilitiesTop3 = [];
if ($resTop = $conn->query($sqlTop3)) {
    while ($row = $resTop->fetch_assoc()) {
        $styles = $facilityStyles[$row['id']] ?? $facilityStyles[array_rand($facilityStyles)];
        $row['bg_color']    = $styles[0];
        $row['title_color'] = $styles[1];
        $row['text_color']  = $styles[2];
        $row['btn_color']   = $styles[3];
        $row['btn_hover']   = $styles[4];
        $popularFacilitiesTop3[] = $row;
    }
}

// All facilities
$sqlAll = "
    SELECT f.facility_id AS id,
           f.name,
           f.description,
           COUNT(b.booking_id) AS popularity
    FROM facilities f
    LEFT JOIN bookings b
        ON b.facility_id = f.facility_id
       AND b.status = 'Confirmed'
    GROUP BY f.facility_id
    ORDER BY popularity DESC
";

$allFacilities = [];
if ($resAll = $conn->query($sqlAll)) {
    while ($row = $resAll->fetch_assoc()) {
        $styles = $facilityStyles[$row['id']] ?? $facilityStyles[array_rand($facilityStyles)];
        $row['bg_color']    = $styles[0];
        $row['title_color'] = $styles[1];
        $row['text_color']  = $styles[2];
        $row['btn_color']   = $styles[3];
        $row['btn_hover']   = $styles[4];
        $allFacilities[] = $row;
    }
}

/* ===== Summary Cards Data ===== */

// Active Bookings (future only, excluding cancelled)
$sqlActive = "SELECT COUNT(*) AS count
              FROM bookings
              WHERE student_id = ?
                AND (
                    date > CURDATE()
                    OR (date = CURDATE() AND end_time > CURRENT_TIME)
                )
                AND status <> 'Cancelled'";
$stmtActive = $conn->prepare($sqlActive);
$studentId = (int)$_SESSION['student_id'];
$stmtActive->bind_param("i", $studentId);
$stmtActive->execute();
$active_count = $stmtActive->get_result()->fetch_assoc()['count'] ?? 0;

// Favorite Facility (most booked since start of year, excluding cancelled)
$sqlFav = "SELECT f.name, COUNT(*) AS total
           FROM bookings b
           JOIN facilities f ON b.facility_id = f.facility_id
           WHERE b.student_id = ?
             AND b.date >= DATE_FORMAT(CURDATE(), '%Y-01-01')
             AND b.status <> 'Cancelled'
           GROUP BY f.name
           ORDER BY total DESC
           LIMIT 1";
$stmtFav = $conn->prepare($sqlFav);
$stmtFav->bind_param("i", $studentId);
$stmtFav->execute();
$favRow = $stmtFav->get_result()->fetch_assoc();
$fav_name = $favRow['name'] ?? 'N/A';
$fav_total = (int)($favRow['total'] ?? 0);

// Next Booking (soonest upcoming, excluding cancelled)
$sqlNext = "SELECT f.name, b.date, b.start_time, b.end_time
            FROM bookings b
            JOIN facilities f ON b.facility_id = f.facility_id
            WHERE b.student_id = ?
              AND (
                    b.date > CURDATE()
                    OR (b.date = CURDATE() AND b.start_time > CURRENT_TIME)
                  )
              AND b.status <> 'Cancelled'
            ORDER BY b.date ASC, b.start_time ASC
            LIMIT 1";
$stmtNext = $conn->prepare($sqlNext);
$stmtNext->bind_param("i", $studentId);
$stmtNext->execute();
$nextRow = $stmtNext->get_result()->fetch_assoc();
$next_name = $nextRow['name'] ?? 'N/A';
$next_date = isset($nextRow['date']) ? date('M j, Y', strtotime($nextRow['date'])) : '';
$next_time = isset($nextRow['start_time'])
        ? date('g:i A', strtotime($nextRow['start_time'])) . ' – ' . date('g:i A', strtotime($nextRow['end_time']))
        : '';
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>User Dashboard</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Your custom CSS -->
    <link rel="stylesheet" href="../../public/css/index.css">
</head>
<body class="flex h-screen overflow-hidden">
<div class="flex-1 flex flex-col overflow-hidden">
    <?php require_once 'partials/header.php'; ?>
    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">

        <!-- Flash Messages -->
        <?php if (isset($_GET['success'])): ?>
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Welcome Back, <?= $user_name ?>!</h2>
        <p class="text-gray-600 mb-6">Here’s an overview of your bookings and available facilities.</p>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Active Bookings -->
            <div class="bg-white rounded-xl shadow-md p-6 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Active Bookings</p>
                    <h3 class="text-3xl font-bold text-gray-800"><?= $active_count ?></h3>
                    <p class="text-gray-500 text-sm mt-1">Upcoming this week</p>
                </div>
                <div class="text-gray-400"><i class="fas fa-circle text-xs"></i></div>
            </div>
            <!-- Favorite Facility -->
            <div class="bg-white rounded-xl shadow-md p-6 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Favorite Facility</p>
                    <h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($fav_name) ?></h3>
                    <p class="text-gray-500 text-sm mt-1">Booked <?= $fav_total ?> times this semester</p>
                </div>
                <div class="text-yellow-400"><i class="fas fa-star text-2xl"></i></div>
            </div>
            <!-- Next Booking -->
            <div class="bg-white rounded-xl shadow-md p-6 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Next Booking</p>
                    <h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($next_name) ?></h3>
                    <?php if ($next_date && $next_time): ?>
                        <p class="text-gray-500 text-sm mt-1"><?= $next_date ?>, <?= $next_time ?></p>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm mt-1">No upcoming booking</p>
                    <?php endif; ?>
                </div>
                <div class="text-purple-400"><i class="fas fa-clock text-2xl"></i></div>
            </div>
        </div>

        <!-- Upcoming Bookings Table -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Upcoming Bookings</h3>
                <div class="flex items-center space-x-4">
                    <button type="button" class="text-blue-500 hover:underline text-sm create-booking">Create Booking</button>
                    <?php if (!isset($_GET['all_bookings'])): ?>
                        <a href="?all_bookings=1" id="viewAllBtn" class="text-blue-500 hover:underline text-sm">View All</a>
                    <?php else: ?>
                        <a href="?" id="showLessBtn" class="text-blue-500 hover:underline text-sm">Show Less</a>
                    <?php endif; ?>

                    <!-- Hide Past checkbox -->
                    <label class="flex items-center space-x-1 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" id="hidePastCheckbox" class="rounded border-gray-300">
                        <span>Hide Past</span>
                    </label>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">Facility</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="bookingsTable">
                    <?php if (!empty($bookings)): ?>
                        <?php
                        $now = new DateTime();
                        foreach ($bookings as $b):
                            $endDt = new DateTime($b['date'] . ' ' . $b['end_time']);
                            $isPast = $endDt < $now;

                            if ($isPast) {
                                $badgeText = 'Past';
                                $badgeClass = 'bg-red-100 text-red-800';
                            } elseif ($b['status'] === 'Cancelled') {
                                $badgeText = 'Cancelled';
                                $badgeClass = 'bg-red-100 text-red-800';
                            } elseif ($b['status'] === 'Confirmed') {
                                $badgeText = 'Confirmed';
                                $badgeClass = 'bg-green-100 text-green-800';
                            } else {
                                $badgeText = htmlspecialchars($b['status'] ?? 'Pending');
                                $badgeClass = 'bg-yellow-100 text-yellow-800';
                            }

                            $canCancel = !$isPast && $b['status'] !== 'Cancelled';
                            ?>
                            <tr data-past="<?= $isPast ? '1' : '0' ?>">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($b['facility_name']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars(date('M j, Y', strtotime($b['date']))) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars(date('g:i A', strtotime($b['start_time']))) ?> –
                                    <?= htmlspecialchars(date('g:i A', strtotime($b['end_time']))) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm flex items-center justify-between">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $badgeClass ?>">
                                <?= $badgeText ?>
                            </span>
                                    <?php if ($canCancel && !empty($b['booking_id'])): ?>
                                        <button type="button"
                                                class="cancel-btn text-red-600 hover:text-red-800 ml-2"
                                                title="Cancel booking"
                                                data-id="<?= (int)$b['booking_id'] ?>">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-sm text-gray-500">No upcoming bookings</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Popular Facilities -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Popular Facilities</h3>
                <button type="button" id="toggleFacilitiesBtn"
                        class="text-blue-500 hover:underline text-sm view-all-btn">
                    View All
                </button>
            </div>

            <!-- Top 3 Facilities -->
            <div id="topFacilities" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($popularFacilitiesTop3 as $f): ?>
                    <?php
                    // Ensure we have styles even if not set in DB
                    $bg    = htmlspecialchars($f['bg_color'] ?? 'bg-gray-100');
                    $title = htmlspecialchars($f['title_color'] ?? 'text-gray-800');
                    $text  = htmlspecialchars($f['text_color'] ?? 'text-gray-700');
                    $btn   = htmlspecialchars($f['btn_color'] ?? 'bg-gray-600');
                    $hover = htmlspecialchars($f['btn_hover'] ?? 'bg-gray-700');
                    ?>
                    <div class="<?= $bg ?> rounded-xl shadow-md p-6 flex flex-col justify-between">
                        <div>
                            <h4 class="text-2xl font-bold <?= $title ?> mb-2">
                                <?= htmlspecialchars($f['name']) ?>
                            </h4>
                            <p class="<?= $text ?> text-sm mb-4">
                                <?= htmlspecialchars($f['description']) ?>
                            </p>
                        </div>
                        <button class="<?= $btn ?> text-white py-2 px-4 rounded-lg hover:<?= $hover ?> transition shadow-md text-center book-now-btn"
                                data-facility-id="<?= $f['id'] ?>"
                                data-facility-name="<?= htmlspecialchars($f['name']) ?>">
                            Book Now
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- All Facilities (Hidden by Default) -->
            <div id="allFacilities" class="grid grid-cols-1 md:grid-cols-3 gap-6 hidden">
                <?php foreach ($allFacilities as $f): ?>
                    <?php
                    $bg    = htmlspecialchars($f['bg_color'] ?? 'bg-gray-100');
                    $title = htmlspecialchars($f['title_color'] ?? 'text-gray-800');
                    $text  = htmlspecialchars($f['text_color'] ?? 'text-gray-700');
                    $btn   = htmlspecialchars($f['btn_color'] ?? 'bg-gray-600');
                    $hover = htmlspecialchars($f['btn_hover'] ?? 'bg-gray-700');
                    ?>
                    <div class="<?= $bg ?> rounded-xl shadow-md p-6 flex flex-col justify-between">
                        <div>
                            <h4 class="text-2xl font-bold <?= $title ?> mb-2">
                                <?= htmlspecialchars($f['name']) ?>
                            </h4>
                            <p class="<?= $text ?> text-sm mb-4">
                                <?= htmlspecialchars($f['description']) ?>
                            </p>
                        </div>
                        <button class="<?= $btn ?> text-white py-2 px-4 rounded-lg hover:<?= $hover ?> transition shadow-md text-center book-now-btn"
                                data-facility-id="<?= $f['id'] ?>"
                                data-facility-name="<?= htmlspecialchars($f['name']) ?>">
                            Book Now
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </main>
</div>

<!-- Edit Booking Modal -->
<?php include 'partials/user_booking_modal.php';?>

<!-- Inject PHP facilities into JS -->
<script>
	window.availableFacilities = <?= json_encode($availableFacilities, JSON_UNESCAPED_SLASHES) ?>;
</script>
<!-- Main JS -->
<script src="../../public/js/index.js"></script>
</body>
</html>
