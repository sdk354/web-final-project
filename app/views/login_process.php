<?php
session_start();

require_once '../core/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['student_id'] = $user['student_id'];
            $_SESSION['name'] = $user['name'];

            header("Location: ../views/userpage.php");
            exit;
        } else {
            echo "Invalid password";
        }
    } else {
        echo "Email not found";
    }

    $stmt->close();
    $conn->close();
}
?>