<?php
require_once 'db_connect.php'; // adjust path if needed

// --- 1. Delete all existing users ---
$conn->query("DELETE FROM students");
$conn->query("DELETE FROM admins");

// --- 2. Prepare new user data ---
$studentName = "User";
$studentEmail = "test@test.com";
$studentPassword = password_hash("password123", PASSWORD_DEFAULT);

$adminName = "Admin";
$adminEmail = "admin@test.com";
$adminPassword = password_hash("password123", PASSWORD_DEFAULT);

// --- 3. Insert new student ---
$stmt = $conn->prepare("INSERT INTO students (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $studentName, $studentEmail, $studentPassword);
if (!$stmt->execute()) {
    die("Error creating student: " . $stmt->error);
}
$stmt->close();

// --- 4. Insert new admin ---
$stmt = $conn->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $adminName, $adminEmail, $adminPassword);
if (!$stmt->execute()) {
    die("Error creating admin: " . $stmt->error);
}
$stmt->close();

$conn->close();

echo "Database reset complete. 
New student: {$studentEmail} / password123 
New admin: {$adminEmail} / password123";
?>
