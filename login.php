<?php
require_once 'connection.php';
session_start();
$error = "";
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $stmt = mysqli_prepare($conn, "SELECT id, fullname, password, role FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] === 'admin') {
                header("Location: admin-dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with that email address.";
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - E-Commerce Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-xl shadow-lg max-w-md w-full">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Store Login</h2>
        
        <?php if (!empty($error)): ?>
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" class="w-full border rounded-md p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" class="w-full border rounded-md p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <button type="submit" name="login" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300 font-semibold shadow-md">Login</button>
        </form>
        <p class="text-sm text-center text-gray-600 mt-4">Don't have an account? <a href="register.php" class="text-blue-600 hover:underline">Register here</a></p>
    </div>
</body>
</html>
