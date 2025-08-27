<?php
// Show all errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_connect.php'; // This defines $conn (MySQLi)

// Facilities data: name, description, opening_time, closing_time, max_capacity
$facilities = [
    ['Study Hall A', 'Located in the Faculty of Business', '09:00:00', '17:00:00', 30],
    ['Study Hall B', 'Located in the Faculty of Computing', '09:00:00', '17:00:00', 25],
    ['Study Hall C', 'Located in the Faculty of Engineering', '09:00:00', '17:00:00', 25],
    ['Badminton Court', 'Indoor court with professional flooring and lighting', '09:00:00', '16:30:00', 4],
    ['Swimming Pool', 'Olympic-sized pool with 8 lanes and seating area', '09:00:00', '16:30:00', 50],
    ['Gym', 'Fully equipped fitness center with cardio and weight machines', '09:00:00', '16:30:00', 40],
    ['Table Tennis', 'Indoor tables with professional-grade equipment', '09:00:00', '16:30:00', 8]
];

// Prepare statements
$checkStmt = $conn->prepare("SELECT facility_id FROM facilities WHERE name = ?");
$insertStmt = $conn->prepare("
    INSERT INTO facilities (name, description, availability, opening_time, closing_time, max_capacity)
    VALUES (?, ?, 1, ?, ?, ?)
");

foreach ($facilities as $facility) {
    [$name, $desc, $open, $close, $cap] = $facility;

    // Check if facility already exists
    $checkStmt->bind_param("s", $name);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "Skipping: '$name' already exists.<br>";
        continue;
    }

    // Insert new facility
    $insertStmt->bind_param("ssssi", $name, $desc, $open, $close, $cap);
    if ($insertStmt->execute()) {
        echo "Inserted: '$name' successfully.<br>";
    } else {
        echo "Error inserting '$name': " . $insertStmt->error . "<br>";
    }
}

echo "<br>Seeding complete.";

$checkStmt->close();
$insertStmt->close();
$conn->close();
