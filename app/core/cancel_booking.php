<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/database/db_connect.php';
require_once __DIR__ . '/controllers/BookingController.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$bookingId = (int)($_POST['booking_id'] ?? 0);
$studentId = (int)($_SESSION['student_id'] ?? 0);

// Check if AJAX
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// If AJAX, send JSON
if ($isAjax) {
    header('Content-Type: application/json');
}

if ($bookingId <= 0 || $studentId <= 0) {
    $msg = 'Invalid parameters';
    if ($isAjax) { echo json_encode(['success' => false, 'error' => $msg]); exit; }
    header('Location: ../views/user_page.php?error=' . urlencode($msg));
    exit;
}

// Fetch booking
$stmt = $conn->prepare("SELECT date, end_time, student_id FROM bookings WHERE booking_id = ? LIMIT 1");
$stmt->bind_param('i', $bookingId);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

if (!$booking) {
    $msg = 'Booking not found';
    if ($isAjax) { echo json_encode(['success' => false, 'error' => $msg]); exit; }
    header('Location: ../views/user_page.php?error=' . urlencode($msg));
    exit;
}

// Ownership check
if ((int)$booking['student_id'] !== $studentId) {
    $msg = 'Not your booking';
    if ($isAjax) { echo json_encode(['success' => false, 'error' => $msg]); exit; }
    header('Location: ../views/user_page.php?error=' . urlencode($msg));
    exit;
}

// Time check in PHP
$bookingEnd = strtotime($booking['date'] . ' ' . $booking['end_time']);
if ($bookingEnd < time()) {
    $msg = 'Booking already ended';
    if ($isAjax) { echo json_encode(['success' => false, 'error' => $msg]); exit; }
    header('Location: ../views/user_page.php?error=' . urlencode($msg));
    exit;
}

// Delete booking
$del = $conn->prepare("DELETE FROM bookings WHERE booking_id = ? AND student_id = ?");
$del->bind_param('ii', $bookingId, $studentId);
$del->execute();

if ($del->affected_rows > 0) {
    $msg = 'Booking cancelled successfully';
    $success = true;
} else {
    $msg = 'Unable to cancel booking';
    $success = false;
}
$del->close();
$conn->close();

if ($isAjax) {
    echo json_encode([
        'success' => $success,
        'message' => $msg,
        'id'      => $bookingId
    ]);
    exit;
}

header('Location: ../views/user_page.php?' . ($success ? 'success=' : 'error=') . urlencode($msg));
exit;
