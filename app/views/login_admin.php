<?php
require_once '../core/auth_check.php';
require_once '../core/db_connect.php';

$email_error = '';
$password_error = '';

if (isset($_SESSION['admin_loggedin']) && $_SESSION['admin_loggedin'] === true) {
    header("Location: ../views/admin_page.php");
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];

    // Assuming you have an 'admins' table
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $admin["password"])) {
            $_SESSION['admin_loggedin'] = true;
            $_SESSION['role'] = 'admin';
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['name'];

            // Redirect to admin dashboard
            header("Location: ../views/admin_page.php");
            exit;
        } else {
            $password_error = "Invalid password";
        }
    } else {
        $email_error = "Email not found";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - NSBM Management Portal</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../../public/js/index.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/index.css">
</head>
<body id="page-login" class="admin-login">
<div class="w-full max-w-md bg-white rounded-3xl shadow-2xl">
    <div class="p-8 sm:p-12 md:p-16 flex flex-col justify-center items-center">
        <div class="w-full">
            <div class="text-center mb-8">
                <div class="text-green-600 mb-4 inline-block">
                    <span class="building-icon w-12 h-12"></span>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Admin Login</h2>
                <p class="text-gray-500 text-sm">Access the Admin Dashboard</p>
            </div>

            <form action="login_admin.php" method="POST" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <input type="email" id="email" name="email" placeholder="Enter your admin email"
                               class="w-full px-4 py-3 rounded-xl border <?php echo !empty($email_error) ? 'border-red-500' : 'border-gray-300'; ?> focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition duration-200"
                               aria-label="Email Address" required>
                        <?php if (!empty($email_error)): ?>
                            <div class="absolute left-full ml-3 top-1/2 transform -translate-y-1/2 z-10">
                                <div class="relative bg-white border border-red-500 text-red-500 text-xs rounded-lg px-3 py-2 shadow-lg w-max">
                                    <?php echo $email_error; ?>
                                    <!-- Arrow -->
                                    <div class="absolute top-1/2 -translate-y-1/2 w-0 h-0 -left-2 border-t-8 border-b-8 border-r-8 border-t-transparent border-b-transparent border-r-red-500"></div>
                                    <div class="absolute top-1/2 -translate-y-1/2 w-0 h-0 -left-[7px] border-t-[7px] border-b-[7px] border-r-[7px] border-t-transparent border-b-transparent border-r-white"></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Enter your password"
                               class="w-full px-4 py-3 rounded-xl border <?php echo !empty($password_error) ? 'border-red-500' : 'border-gray-300'; ?> focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition duration-200 pr-10"
                               aria-label="Password" required>
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none" aria-label="Toggle Password Visibility">
                            <span class="eye-icon" id="eyeIcon"></span>
                        </button>
                        <?php if (!empty($password_error)): ?>
                            <div class="absolute left-full ml-3 top-1/2 transform -translate-y-1/2 z-10">
                                <div class="relative bg-white border border-red-500 text-red-500 text-xs rounded-lg px-3 py-2 shadow-lg w-max">
                                    <?php echo $password_error; ?>
                                    <!-- Arrow -->
                                    <div class="absolute top-1/2 -translate-y-1/2 w-0 h-0 -left-2 border-t-8 border-b-8 border-r-8 border-t-transparent border-b-transparent border-r-red-500"></div>
                                    <div class="absolute top-1/2 -translate-y-1/2 w-0 h-0 -left-[7px] border-t-[7px] border-b-[7px] border-r-[7px] border-t-transparent border-b-transparent border-r-white"></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-xl bg-gradient-to-r from-green-500 to-blue-500 text-white font-semibold text-lg shadow-lg hover:from-green-600 hover:to-blue-600 transition duration-300 ease-in-out">
                    Login
                </button>
            </form>

            <div class="text-center mt-6">
                <a href="login.php" class="text-sm text-gray-600 hover:text-green-500 transition-colors">
                    &larr; Back to login options
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>