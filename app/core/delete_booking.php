<?php
// delete_booking.php
ini_set('display_errors',1);
error_reporting(E_ALL);
require_once __DIR__ . '/../core/auth_check.php';
require_once __DIR__ . '/../core/database/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;
    if ($id > 0) {
        $conn->query("DELETE FROM bookings WHERE booking_id = {$id}");
    }
}

header('Location: ../views/admin_page.php?msg=deleted');
exit;
