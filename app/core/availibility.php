<?php
include 'db_connect.php';

// If booking parameters are passed in URL, insert into database
if (isset($_GET['facility_id'], $_GET['date'], $_GET['start'], $_GET['end'])) {
    $facility_id = intval($_GET['facility_id']);
    $date = $_GET['date'];
    $start = $_GET['start'];
    $end = $_GET['end'];

    $stmt = $conn->prepare("INSERT INTO bookings (facility_id, date, start_time, end_time, status) VALUES (?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("isss", $facility_id, $date, $start, $end);
    $stmt->execute();
    $stmt->close();

    // Redirect to avoid rebooking on refresh
    header("Location: availibility.php?facility_id=$facility_id&success=1");
    exit();
}

// Example facility (replace with your real query)
$facility = ['facility_id' => 1];
$facility_id = $facility['facility_id'];
$currentDate = date('Y-m-d');

// Example time slots (replace with your own data)
$timeSlots = [
    ['start' => '09:00:00', 'end' => '10:00:00'],
    ['start' => '10:00:00', 'end' => '11:00:00'],
    ['start' => '11:00:00', 'end' => '12:00:00']
];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Facility Availability</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; width: 50%; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background: #f4f4f4; }
        .available { background-color: #d4f8d4; }
        .unavailable { background-color: #f8d4d4; }
        a.button {
            display: inline-block;
            padding: 6px 12px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        a.button.disabled {
            background-color: #aaa;
            pointer-events: none;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Availability for Facility <?php echo $facility_id; ?> on <?php echo $currentDate; ?></h2>

<table>
    <tr>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Status</th>
    </tr>
    <?php
    foreach ($timeSlots as $slot) {
        $start = $slot['start'];
        $end = $slot['end'];

        $checkBooking = $conn->query("SELECT * FROM bookings 
            WHERE facility_id = $facility_id 
            AND date = '$currentDate' 
            AND start_time = '$start' 
            AND end_time = '$end'");

        if ($checkBooking->num_rows == 0) {
            // Available
            echo "<tr class='available'>
                    <td>$start</td>
                    <td>$end</td>
                    <td><a class='button' href='availibility.php?facility_id=$facility_id&date=$currentDate&start=$start&end=$end'>Book</a></td>
                  </tr>";
        } else {
            // Unavailable
            echo "<tr class='unavailable'>
                    <td>$start</td>
                    <td>$end</td>
                    <td><a class='button disabled'>Unavailable</a></td>
                  </tr>";
        }
    }
    ?>
</table>

</body>
</html>
