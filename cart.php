<?php
require_once 'connection.php';
session_start();
if (isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $id => $quantity) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id]['quantity'] = intval($quantity);
        }
    }
}
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    unset($_SESSION['cart'][$remove_id]);
    header("Location: cart.php");
    exit();
}
$checkout_msg = "";
if (isset($_POST['checkout'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    if (!empty($_SESSION['cart'])) {
        $user_id = $_SESSION['user_id'];
        $total_amount = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }
        $stmt = mysqli_prepare($conn, "INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'Pending')");
        mysqli_stmt_bind_param($stmt, "id", $user_id, $total_amount);
        
        if (mysqli_stmt_execute($stmt)) {
            unset($_SESSION['cart']); // Clear cart after successful order
            $checkout_msg = "Order placed successfully!";
        } else {
            $checkout_msg = "Checkout failed. Please try again.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart - TechVault</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans min-h-screen flex flex-col">
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex items-center justify-between">
            <a href="index.php" class="text-xl font-extrabold text-blue-600">TechVault Store</a>
            <a href="index.php" class="text-gray-700 hover:text-blue-600 font-medium">Continue Shopping</a>
        </div>
    </header>
    <main class="container mx-auto px-6 py-12 flex-grow max-w-4xl">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Your Shopping Cart</h1>
        <?php if (!empty($checkout_msg)): ?>
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg text-center font-medium"><?php echo $checkout_msg; ?> <a href="index.php" class="underline font-bold">Return Home</a></div>
        <?php endif; ?>
        <?php if (empty($_SESSION['cart'])): ?>
            <div class="bg-white p-8 rounded-xl shadow-md text-center">
                <p class="text-gray-600 mb-4">Your cart is currently empty.</p>
                <a href="index.php" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold shadow">Start Shopping</a>
            </div>
        <?php else: ?>
            <form action="cart.php" method="POST">
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 text-sm uppercase">
                                <th class="p-4 border-b">Product</th>
                                <th class="p-4 border-b">Price</th>
                                <th class="p-4 border-b">Quantity</th>
                                <th class="p-4 border-b">Subtotal</th>
                                <th class="p-4 border-b">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm">
                            <?php 
                            $grand_total = 0;
                            foreach ($_SESSION['cart'] as $id => $item): 
                                $subtotal = $item['price'] * $item['quantity'];
                                $grand_total += $subtotal;
                            ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-4 font-medium text-gray-900"><?php echo htmlspecialchars($item['name']); ?></td>
                                <td class="p-4">₱<?php echo number_format($item['price'], 2); ?></td>
                                <td class="p-4">
                                    <input type="number" name="qty[<?php echo $id; ?>]" value="<?php echo $item['quantity']; ?>" min="1" class="w-16 border rounded p-1 text-center outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="p-4 font-semibold text-gray-900">₱<?php echo number_format($subtotal, 2); ?></td>
                                <td class="p-4">
                                    <a href="cart.php?remove=<?php echo $id; ?>" class="text-red-500 hover:underline font-medium">Remove</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="flex flex-col md:flex-row justify-between items-center bg-white p-6 rounded-xl shadow-md gap-4">
                    <button type="submit" name="update_cart" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition font-medium">Update Cart</button>
                    
                    <div class="text-right">
                        <p class="text-lg text-gray-600 mb-1">Grand Total: <span class="text-2xl font-black text-blue-600">₱<?php echo number_format($grand_total, 2); ?></span></p>
                        <button type="submit" name="checkout" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition font-semibold shadow-md">Proceed to Checkout</button>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </main>
    <footer class="bg-white border-t border-gray-200 py-6 text-center text-gray-500 text-sm">
        <p>&copy; 2026 TechVault E-Commerce Store.</p>
    </footer>
</body>
</html>