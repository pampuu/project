<?php
session_start();
include 'db.php';
include 'header.php';

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $user_id = $_SESSION['user_id'];
    $total = 0;

    // Calculate total price from the cart
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Prepare and execute the query to get product details securely
        $sql = "SELECT * FROM products WHERE id = :product_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $total += $product['price'] * $quantity;
        }
    }

    // Insert the order details into the 'orders' table
    $sql = "INSERT INTO orders (user_id, total) VALUES (:user_id, :total)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':total', $total, PDO::PARAM_STR);  // Use STR for decimal values
    $stmt->execute();

    // Get the inserted order ID
    $order_id = $conn->lastInsertId();

    // Insert order items into the 'order_items' table
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Clear the cart session after placing the order
    unset($_SESSION['cart']);

    // Add Cash on Delivery information
    echo "<h1 class='carthead'>Payment Method</h1>";
    echo "<div><strong>Payment Method: Cash on Delivery</strong></div>";
    echo "<p>Please have the exact amount ready upon delivery.</p>";
    echo "<a id='button' style='margin-bottom:70px' class='btn'>Submit Order</a>";
} else {
    // If the cart is empty, show a message
    echo "<p>Your cart is empty.</p>";
}

include 'footer.php';
?>

<script>
    document.getElementById('button').addEventListener("click", () => {
        alert('Thank you for your order! Your payment will be collected in cash upon delivery.');
        window.location.href = "index.php";
    });
</script>