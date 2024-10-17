<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];

// Delete product from the database
$stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();

header('Location: admin_dashboard.php');
exit;
?>
