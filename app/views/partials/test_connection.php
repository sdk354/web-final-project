<?php
// test_connection.php

// Include the file that contains our connection logic
require_once 'db_connect.php';

// If the script gets to this line without dying, the connection was a success!
echo "Database connection successful!";

// You can also prove it's connected by running a simple query
$result = $conn->query("SELECT name FROM facilities");

if ($result) {
    echo "<br>Successfully queried the 'facilities' table.";
} else {
    echo "<br>Error querying the database: " . $conn->error;
}

// Close the connection
$conn->close();
?>
