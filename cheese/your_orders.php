<?php 
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user_id'];
$ran = [
    3580214688, 6700099836, 3991091367, 9315975144, 8827114272,
    1347796062, 2445442397, 6645755707, 2168773033, 7768627276,
    9205298774, 7804010539, 3787114073, 7843608648,
];

// Fetch all orders for the logged-in user
$stmt = $conn->prepare("SELECT o.*, u.email FROM orders o JOIN users u ON o.user_id = u.id WHERE o.user_id = :user_id");
$stmt->bindParam(':user_id', $user, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'header.php'; ?>

<style>
    .orders-container {
        width: 90%;
        margin: 30px auto;
        background-color: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .orders-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .orders-header h2 {
        font-size: 2rem;
        color: #333;
        margin-bottom: 10px;
        border-bottom: 2px solid #E1AD01;
        padding-bottom: 5px;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        margin-bottom: 20px;
        color: #333;
    }

    .orders-table thead tr {
        background-color: #f9f9f9;
    }

    .orders-table th, .orders-table td {
        padding: 12px 15px;
        border: 1px solid #ddd;
    }

    .orders-table th {
        font-size: 1.2rem;
    }

    .orders-table tbody tr:hover {
        background-color: #f4f4f4;
    }

    .order-actions a {
        text-decoration: none;
        color: #007bff;
        font-weight: bold;
        padding: 5px 10px;
        transition: all 0.3s ease;
    }

    .order-actions a:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    .order-status {
        font-weight: bold;
        padding: 5px 10px;
        border-radius: 5px;
        display: inline-block;
    }

    .status-pending {
        background-color: #ffc107;
        color: white;
    }

    .status-preparing {
        background-color: #17a2b8;
        color: white;
    }

    .status-ready {
        background-color: #28a745;
        color: white;
    }

    @media (max-width: 768px) {
        .orders-container {
            padding: 20px;
        }

        .orders-table th, .orders-table td {
            padding: 10px;
        }
    }
</style>

<div class="orders-container">
    <div class="orders-header">
        <h2>Your Orders</h2>
    </div>

    <table class="orders-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>User Email</th>
                <th>Total</th>
                <th>Order Date</th>
                <th>Actions</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $ran[$order['id'] % 14] - $order['id'] ** 2 ?></td>
                    <td><?= htmlspecialchars($order['email']) ?></td>
                    <td>$<?= htmlspecialchars($order['total']) ?></td>
                    <td><?= htmlspecialchars($order['order_date']) ?></td>
                    <td class="order-actions">
                        <a href="view_order.php?id=<?= $order['id'] ?>">View Details</a>
                    </td>
                    <td>
                        <span class="order-status 
                            <?= $order['status'] === 'pending' ? 'status-pending' : 
                               ($order['status'] === 'preparing' ? 'status-preparing' : 
                               'status-ready') ?>">
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
