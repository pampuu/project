<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];
$status = $_GET['status'];

$stmt = $conn->prepare("UPDATE orders SET status = :status WHERE id = :id");
$stmt->bindParam(':status', $status, PDO::PARAM_STR); // assuming $user is an integer
$stmt->bindParam(':id', $id, PDO::PARAM_INT); // assuming $user is an integer
$stmt->execute();

header('Location: admin_dashboard.php');
