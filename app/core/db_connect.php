<?php
//echo "DEBUG: db_connect.php was loaded<br>";

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "booking_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
