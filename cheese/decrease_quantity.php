<?php
session_start();
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Check if the product is in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Decrease the quantity, but don't allow it to go below 1
        if ($_SESSION['cart'][$product_id] > 1) {
            $_SESSION['cart'][$product_id]--;
        } else {
            // If the quantity is 1, you may choose to remove the item entirely from the cart
            unset($_SESSION['cart'][$product_id]);
        }
    }

    // Redirect back to the cart
    header("Location: cart.php");
    exit();
}
?>
