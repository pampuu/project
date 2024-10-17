<?php
session_start();
include 'db.php';

$product_id = $_GET['id'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]++;
} else {
    $_SESSION['cart'][$product_id] = 1;
}

header('Location: cart.php');
?>
