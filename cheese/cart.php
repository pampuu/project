<?php
session_start();
include 'db.php'; // Ensure that db.php uses PDO
include 'header.php';

// Check if the cart exists and has items
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $total = 0;

    // Cart heading
    echo "<h1 style='text-align:center; font-size: 2em; color: #333; margin-bottom: 20px;'>Your Cart</h1>";

    // Loop through each product in the cart
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Prepare the SQL query to fetch the product by its ID
        $sql = "SELECT * FROM products WHERE id = :product_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the product exists
        if ($product) {
            // Display the product details in the cart
            echo "<div class='cart-item' style='border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;'>";
            echo "<p style='font-size: 1.2em; margin: 0;'>" . htmlspecialchars($product['name']) . " - $" . htmlspecialchars($product['price']) . " x $quantity</p>";

            // Add buttons to adjust the quantity
            echo "<div style='display: flex; align-items: center;'>";
            echo "<a href='decrease_quantity.php?id=" . htmlspecialchars($product_id) . "' class='btn' style='background-color: #f00; color: #fff; padding: 5px 10px; margin-right: 5px; text-decoration: none;'>-</a>";
            echo "<span style='font-size: 1.2em; margin: 0 10px;'>$quantity</span>";
            echo "<a href='increase_quantity.php?id=" . htmlspecialchars($product_id) . "' class='btn' style='background-color: #0f0; color: #fff; padding: 5px 10px; text-decoration: none;'>+</a>";

            // Button to remove the product from the cart
            echo "<a href='remove_from_cart.php?id=" . htmlspecialchars($product_id) . "' class='btn' style='background-color: #333; color: #fff; padding: 5px 10px; margin-left: 10px; text-decoration: none;'>Remove</a>";
            echo "</div>";
            echo "</div>";

            // Calculate the total price
            $total += $product['price'] * $quantity;
        } else {
            // Handle the case where the product is not found (just in case)
            echo "<p style='color: red;'>Product not found for ID: " . htmlspecialchars($product_id) . "</p>";
        }
    }

    // Display the total price
    echo "<p class='total' style='font-size: 1.5em; text-align: right; margin-top: 20px;'>Total: $" . number_format($total, 2) . "</p>";
    echo "<a href='checkout.php' class='btn' style='display: block; width: 100%; text-align: center; background-color: #333; color: #fff; padding: 10px; text-decoration: none; margin-top: 20px;'>Proceed to Checkout</a>";
} else {
    // If the cart is empty
    echo "<p style='text-align:center; font-size: 1.5em; color: #555;'>Your cart is empty.</p>";
}

include 'footer.php';
