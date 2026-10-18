<?php
require_once 'connection.php';
session_start();
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => 1
        ];
    }
    header("Location: index.php");
    exit();
}
$result = mysqli_query($conn, "SELECT * FROM products");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TechVault E-Commerce Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans min-h-screen flex flex-col">
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex items-center justify-between">
            <a href="index.php" class="text-xl font-extrabold text-blue-600">TechVault Store</a>
            <div class="flex items-center space-x-6">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="text-gray-700 text-sm">Welcome, <b><?php echo htmlspecialchars($_SESSION['fullname']); ?></b></span>
                    <a href="cart.php" class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg font-medium hover:bg-blue-100 transition">Cart (<?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0; ?>)</a>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="admin-dashboard.php" class="text-purple-600 font-semibold hover:underline">Admin Panel</a>
                    <?php endif; ?>
                    <a href="logout.php" class="text-red-500 hover:underline text-sm font-medium">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="text-gray-700 hover:text-blue-600 font-medium">Login</a>
                    <a href="register.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <section class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-16 text-center">
        <div class="container mx-auto px-6">
            <h1 class="text-4xl md:text-5xl font-black mb-4">Discover Cutting-Edge Tech Gear</h1>
            <p class="text-lg md:text-xl text-blue-100 max-w-2xl mx-auto">Explore high-performance peripherals, hardware essentials, and developer tools built for modern workflows.</p>
        </div>
    </section>
    <main class="container mx-auto px-6 py-12 flex-grow">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Featured Products</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 flex flex-col justify-between hover:shadow-xl transition duration-300">
                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="w-full h-48 object-cover">
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($row['name']); ?></h3>
                            <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars($row['description']); ?></p>
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <span class="text-xl font-black text-blue-600">₱<?php echo number_format($row['price'], 2); ?></span>
                            <form action="index.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($row['name']); ?>">
                                <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                                <button type="submit" name="add_to_cart" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 text-sm font-semibold shadow">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </main>
    <footer class="bg-white border-t border-gray-200 py-6 text-center text-gray-500 text-sm">
        <p>&copy; 2026 TechVault E-Commerce Store. Web Application Development Final Project.</p>
    </footer>
</body>
</html>