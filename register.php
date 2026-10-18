<?php
require_once 'connection.php';
session_start();
$error = "";
$success = "";
if (isset($_POST['register'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    if (empty($fullname) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, 'customer')");
        mysqli_stmt_bind_param($stmt, "sss", $fullname, $email, $hashed_password);
        if (mysqli_stmt_execute($stmt)) {
            $success = "Registration successful! You can now log in.";
        } else {
            $error = "Email is already registered or database error occurred.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - E-Commerce Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-xl shadow-lg max-w-md w-full">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Create an Account</h2>
        
        <?php if (!empty($error)): ?>
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded text-sm"><?php echo $success; ?></div>
        <?php endif; ?>
        <form action="register.php" method="POST" class="space-y-4" onsubmit="return validateForm()">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" id="fullname" name="fullname" class="w-full border rounded-md p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" id="email" name="email" class="w-full border rounded-md p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" class="w-full border rounded-md p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <button type="submit" name="register" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300 font-semibold shadow-md">Register</button>
        </form>
        <p class="text-sm text-center text-gray-600 mt-4">Already have an account? <a href="login.php" class="text-blue-600 hover:underline">Login here</a></p>
    </div>
    <script>
        function validateForm() {
            let pass = document.getElementById("password").value;
            if (pass.length < 6) {
                alert("Password must be at least 6 characters long.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>