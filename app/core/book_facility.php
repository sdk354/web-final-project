<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/database/db_connect.php';
require_once __DIR__ . '/controllers/BookingController.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: ../views/login.php");
    exit;
}

// Sanitize and set defaults
$facilityId = isset($_POST['facility_id']) ? intval($_POST['facility_id']) : 0;
$date       = $_POST['date']   ?? date('Y-m-d', strtotime('+1 day'));
$start      = $_POST['start']  ?? '10:00:00';
$end        = $_POST['end']    ?? '12:00:00';
$status     = $_POST['status'] ?? 'Pending'; // default matches schema

$bookingController = new BookingController($conn);
$result = $bookingController->createBooking(
    $_SESSION['student_id'],
    $facilityId,
    $date,
    $start,
    $end,
    $status
);

if ($result['success']) {
    header("Location: /webproject/app/views/user_page.php?success=" . urlencode("Booking created successfully"));
} else {
    header("Location: /webproject/app/views/user_page.php?error=" . urlencode($result['message']));
}
exit;
