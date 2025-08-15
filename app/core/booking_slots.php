<?php
include 'db_connect.php';

if (isset($_GET['facility_id']) && isset($_GET['start']) && isset($_GET['end'])) {
    $facility_id = intval($_GET['facility_id']);
    $start = $_GET['start'];
    $end = $_GET['end'];
    $date = date('Y-m-d');

    // Insert booking
    $stmt = $conn->prepare("INSERT INTO bookings (facility_id, date, start_time, end_time) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $facility_id, $date, $start, $end);
    if ($stmt->execute()) {
        echo "Booking confirmed for Facility ID $facility_id from $start to $end on $date.";
    } else {
        echo "Error booking: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
