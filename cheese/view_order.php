<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];

$ran = [
    3580214688,
    6700099836,
    3991091367,
    9315975144,
    8827114272,
    1347796062,
    2445442397,
    6645755707,
    2168773033,
    7768627276,
    9205298774,
    7804010539,
    3787114073,
    7843608648,
];


// Fetch order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch ordered products
$stmt = $conn->prepare("SELECT order_items.*, products.name,products.price,products.image FROM order_items JOIN products ON order_items.product_id = products.id WHERE order_items.order_id = :order_id");
$stmt->bindParam(':order_id', $id);
$stmt->execute();
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'header.php'; ?>

<div class="container">
    <h1>Order Details (Order ID: <?= $ran[$order['id'] % 14] - $order['id'] ** 2  ?>)</h1>
    <p>User ID: <?= $order['user_id'] ?></p>
    <p>Total: <?= $order['total'] ?></p>
    <p>Order Date: <?= $order['order_date'] ?></p>

    <h2>Ordered Products</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Image</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($order_items as $item): ?>
                <tr>
                    <td><?= $item['name'] ?></td>
                    <td><img width="100" src="<?= $item['image'] ?>" /></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= $item['price'] * $item['quantity']  ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>