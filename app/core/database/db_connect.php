<?php
//die("DEBUG: db_connect.php was successfully included!");

$servername = "127.0.0.1";
$username = "root";
$password = "abcd1234";
$dbname = "booking_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>