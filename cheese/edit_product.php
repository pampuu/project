<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];

// Fetch product data
$stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Update product in the database
    $stmt = $conn->prepare("UPDATE products SET name = :name, price = :price, description = :description WHERE id = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    header('Location: admin_dashboard.php');
    exit;
}
?>

<?php include 'header.php'; ?>

<div class="container">
    <h1>Edit Product</h1>
    <form method="POST">
        <label>Product Name</label>
        <input type="text" name="name" value="<?= $product['name'] ?>" required>

        <label>Price</label>
        <input type="number" name="price" value="<?= $product['price'] ?>" step="0.01" required>

        <label>Description</label>
        <textarea name="description"><?= $product['description'] ?></textarea>

        <button type="submit">Update Product</button>
    </form>
</div>

<?php include 'footer.php'; ?>
