<?php
// edit_booking.php
ini_set('display_errors',1);
error_reporting(E_ALL);
require_once __DIR__ . '/../core/auth_check.php';
require_once __DIR__ . '/../core/database/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // sanitize + cast
    $id       = (int) $_POST['booking_id'];
    $date     = $conn->real_escape_string($_POST['date']);
    $start    = $conn->real_escape_string($_POST['start_time']);
    $end      = $conn->real_escape_string($_POST['end_time']);
    $status   = $conn->real_escape_string($_POST['status']);

    $sql = "
      UPDATE bookings
      SET date       = '{$date}',
          start_time = '{$start}',
          end_time   = '{$end}',
          status     = '{$status}'
      WHERE booking_id = {$id}
    ";
    $conn->query($sql);

    header('Location: admin_page.php?msg=updated');
    exit;
}

// GET: load existing
$id = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
$row = $conn
    ->query("SELECT * FROM bookings WHERE booking_id = {$id}")
    ->fetch_assoc();

if (!$row) {
    die('Booking not found');
}

// Render a simple form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Edit Booking #<?= $id ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 bg-gray-100">
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Edit Booking #<?= $id ?></h2>
    <form method="post" class="space-y-4">
        <input type="hidden" name="booking_id" value="<?= $id ?>">

        <label class="block">
            <span class="text-gray-700">Date</span>
            <input
                type="date"
                name="date"
                value="<?= htmlspecialchars($row['date']) ?>"
                class="mt-1 block w-full border-gray-300 rounded"
                required
            />
        </label>

        <label class="block">
            <span class="text-gray-700">Start Time</span>
            <input
                type="time"
                name="start_time"
                value="<?= htmlspecialchars($row['start_time']) ?>"
                class="mt-1 block w-full border-gray-300 rounded"
                required
            />
        </label>

        <label class="block">
            <span class="text-gray-700">End Time</span>
            <input
                type="time"
                name="end_time"
                value="<?= htmlspecialchars($row['end_time']) ?>"
                class="mt-1 block w-full border-gray-300 rounded"
                required
            />
        </label>

        <label class="block">
            <span class="text-gray-700">Status</span>
            <select
                name="status"
                class="mt-1 block w-full border-gray-300 rounded"
                required
            >
                <?php foreach (['Pending','Confirmed','Cancelled'] as $st): ?>
                    <option
                        value="<?= $st ?>"
                        <?= $row['status'] === $st ? 'selected' : '' ?>
                    >
                        <?= $st ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <div class="flex justify-end space-x-2">
            <a
                href="admin_page.php"
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
            >
                Cancel
            </a>
            <button
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
                Save Changes
            </button>
        </div>
    </form>
</div>
</body>
</html>
