<?php
require_once 'db_connect.php';

$name = "Test User";
$email = "test@test.com";
$plainPassword = "password123";

$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO students (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {
    echo "Success! The user 'test@test.com' with password 'password123' has been created.";
} else {
    echo "Error creating user: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
