<?php
require_once 'connection.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
$msg = "";
if (isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = trim($_POST['image_url']);
    $stmt = mysqli_prepare($conn, "INSERT INTO products (name, description, price, stock, image_url) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssdss", $name, $desc, $price, $stock, $image);
    if (mysqli_stmt_execute($stmt)) {
        $msg = "Product successfully added!";
    } else {
        $msg = "Error adding product.";
    }
    mysqli_stmt_close($stmt);
}
$products = mysqli_query($conn, "SELECT * FROM products");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - TechVault</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans min-h-screen">
    <header class="bg-white shadow">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">Admin Control Panel</h1>
            <a href="index.php" class="text-blue-600 hover:underline font-medium">Return to Store</a>
        </div>
    </header>
    <main class="container mx-auto px-6 py-8">
        <?php if(!empty($msg)): ?>
            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded text-sm"><?php echo $msg; ?></div>
        <?php endif; ?>
        <div class="bg-white p-6 rounded-xl shadow-md mb-8">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Add New Product</h2>
            <form action="admin-dashboard.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                    <input type="text" name="name" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱)</label>
                    <input type="number" step="0.01" name="price" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                    <input type="number" name="stock" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
                    <input type="text" name="image_url" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" required></textarea>
                </div>
                <div class="md:col-span-2">
                    <button type="submit" name="add_product" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 font-semibold shadow">Save Product</button>
                </div>
            </form>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Existing Inventory</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 text-sm uppercase">
                            <th class="p-3 border-b">ID</th>
                            <th class="p-3 border-b">Product Name</th>
                            <th class="p-3 border-b">Price</th>
                            <th class="p-3 border-b">Stock</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm">
                        <?php while($row = mysqli_fetch_assoc($products)): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3"><?php echo $row['id']; ?></td>
                            <td class="p-3 font-medium text-gray-900"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="p-3">₱<?php echo number_format($row['price'], 2); ?></td>
                            <td class="p-3"><?php echo $row['stock']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>