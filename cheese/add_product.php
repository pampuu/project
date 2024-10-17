<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $image = $_POST['image'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Insert new product into the database
    $stmt = $conn->prepare("INSERT INTO products (name, image, price, description) VALUES (:name, :image, :price, :description)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    header('Location: admin_dashboard.php');
    exit;
}
?>

<?php include 'header.php'; ?>

<div class="container">
    <h1>Add New Product</h1>
    <form method="POST">
        <label>Product Name</label>
        <input type="text" name="name" required>

        <label>Image Name</label>
        <input type="text" name="image" required>

        <label>Price</label>
        <input type="number" name="price" step="0.01" required>

        <label>Description</label>
        <textarea name="description"></textarea>

        <button type="submit">Add Product</button>
    </form>
</div>

<?php include 'footer.php'; ?>