<?php
require_once 'connection.php';
session_start();
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);
if (!$product) {
    header("Location: index.php");
    exit();
}
if (isset($_POST['add_to_cart'])) {
    $quantity = intval($_POST['quantity']);
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity
        ];
    }
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?> - TechVault Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans min-h-screen flex flex-col">
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex items-center justify-between">
            <a href="index.php" class="text-xl font-extrabold text-blue-600">TechVault Store</a>
            <a href="cart.php" class="text-gray-700 hover:text-blue-600 font-medium">Cart</a>
        </div>
    </header>
    <main class="container mx-auto px-6 py-12 flex-grow max-w-4xl">
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-8 p-8">
            <div>
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-80 object-cover rounded-lg shadow">
            </div>
            <div class="flex flex-col justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 mb-2"><?php echo htmlspecialchars($product['name']); ?></h1>
                    <p class="text-2xl font-black text-blue-600 mb-4">₱<?php echo number_format($product['price'], 2); ?></p>
                    <p class="text-gray-600 text-sm mb-6 leading-relaxed"><?php echo htmlspecialchars($product['description']); ?></p>
                    <p class="text-sm text-gray-500 mb-4">Available Stock: <span class="font-semibold text-gray-800"><?php echo $product['stock']; ?> units</span></p>
                </div>
                <form action="product-detail.php?id=<?php echo $product_id; ?>" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="w-24 border rounded-md p-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <button type="submit" name="add_to_cart" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-300 font-semibold shadow-md">Add to Cart</button>
                </form>
            </div>
        </div>
    </main>
    <footer class="bg-white border-t border-gray-200 py-6 text-center text-gray-500 text-sm">
        <p>&copy; 2026 TechVault E-Commerce Store.</p>
    </footer>
</body>
</html>