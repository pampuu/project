<?php
session_start();
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Check if the product is in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Increase the quantity
        $_SESSION['cart'][$product_id]++;
    }

    // Redirect back to the cart
    header("Location: cart.php");
    exit();
}
?>
