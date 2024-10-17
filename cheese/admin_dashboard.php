<?php
session_start();
include 'db.php';

// Check if the user is logged in and has an admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

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


// Handle employee registration form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['role'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);  // Hash the password
    $role = $_POST['role'];

    // Insert new employee into the users table
    $insert_employee_sql = "INSERT INTO users (username, email, password, role) VALUES (:name, :email, :password, :role)";
    $stmt = $conn->prepare($insert_employee_sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);
    $stmt->execute();

    $message = "Employee registered successfully!";
}
// Fetch all products, orders, users, leave requests, and bookings
$products = $conn->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
$orders = $conn->query("SELECT o.*, u.email FROM orders o JOIN users u ON o.user_id = u.id")->fetchAll(PDO::FETCH_ASSOC);
$users = $conn->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
$leave_requests = $conn->query("SELECT lr.*, u.email FROM leave_requests lr JOIN users u ON lr.user_id = u.id")->fetchAll(PDO::FETCH_ASSOC);
$bookings = $conn->query("SELECT * FROM bookings")->fetchAll(PDO::FETCH_ASSOC);
$messages = $conn->query("SELECT * FROM employee_contact_messages")->fetchAll(PDO::FETCH_ASSOC);
$reviews = $conn->query("SELECT * FROM reviews")->fetchAll(PDO::FETCH_ASSOC);

// Check if a role update has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id']) && isset($_POST['role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];

    // Update the user's role in the database
    $update_role_sql = "UPDATE users SET role = :role WHERE id = :id";
    $stmt = $conn->prepare($update_role_sql);
    $stmt->bindParam(':role', $new_role, PDO::PARAM_STR);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: admin_dashboard.php');
    $message = "User role updated successfully!";
}

// Check if a leave approval or rejection has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['leave_id']) && isset($_POST['action'])) {
    $leave_id = $_POST['leave_id'];
    $action = $_POST['action'];  // approve or reject

    // Update the leave request status
    $update_leave_sql = "UPDATE leave_requests SET status = :status WHERE id = :id";
    $stmt = $conn->prepare($update_leave_sql);
    $stmt->bindParam(':status', $action, PDO::PARAM_STR);
    $stmt->bindParam(':id', $leave_id, PDO::PARAM_INT);
    $stmt->execute();
    header('Location: admin_dashboard.php');
    $message = "Leave request " . ($action == 'approved' ? 'approved' : 'rejected') . " successfully!";
}

// Handle booking approval/rejection
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['booking_id']) && isset($_POST['booking_action'])) {
    $booking_id = $_POST['booking_id'];
    $booking_action = $_POST['booking_action']; // approve, reject, or delete

    if ($booking_action == 'delete') {
        // Delete the booking
        $delete_booking_sql = "DELETE FROM bookings WHERE id = :id";
        $stmt = $conn->prepare($delete_booking_sql);
        $stmt->bindParam(':id', $booking_id, PDO::PARAM_INT);
        $stmt->execute();
        $message = "Booking deleted successfully!";
        header('Location: admin_dashboard.php');
    } else {
        // Update the booking status
        $update_booking_sql = "UPDATE bookings SET status = :status WHERE id = :id";
        $stmt = $conn->prepare($update_booking_sql);
        $stmt->bindParam(':status', $booking_action, PDO::PARAM_STR);
        $stmt->bindParam(':id', $booking_id, PDO::PARAM_INT);
        $stmt->execute();
        $message = "Booking " . ($booking_action == 'approved' ? 'approved' : 'rejected') . " successfully!";
        header('Location: admin_dashboard.php');
    }
}
?>

<?php include 'header.php'; ?>

<div class="container" style="width: 90%; margin: 20px auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
    <h1 style="text-align: center; color: black;">Admin Dashboard</h1>

    <!-- Booking Management Section -->
    <h2 style="border-bottom: 2px solid #ccc; padding: 5px; color: black;">Manage Bookings</h2>
    <?php if (isset($message)) {
        echo "<p style='color: green;'>$message</p>";
    } ?>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left; margin-bottom: 20px; color: black;">
        <thead style="background-color: #f9f9f9;">
            <tr>
                <th>Booking ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Booking Date</th>
                <th>Booking Time</th>
                <th>Guests</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= $booking['id'] ?></td>
                    <td><?= htmlspecialchars($booking['name']) ?></td>
                    <td><?= htmlspecialchars($booking['email']) ?></td>
                    <td><?= htmlspecialchars($booking['phone']) ?></td>
                    <td><?= htmlspecialchars($booking['booking_date']) ?></td>
                    <td><?= htmlspecialchars($booking['booking_time']) ?></td>
                    <td><?= htmlspecialchars($booking['guests']) ?></td>
                    <td><?= ucfirst($booking['status']) ?></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                            <button type="submit" name="booking_action" value="approved" style="color: black; background-color: #28a745; border: none; padding: 5px 10px; border-radius: 4px;">Approve</button>
                            <button type="submit" name="booking_action" value="rejected" style="color: black; background-color: #dc3545; border: none; padding: 5px 10px; border-radius: 4px;">Reject</button>
                            <button type="submit" name="booking_action" value="delete" style="color: black; background-color: #6c757d; border: none; padding: 5px 10px; border-radius: 4px;" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Product Management Section -->
    <h2 style="border-bottom: 2px solid #ccc; padding: 5px; color: black;">Manage Products</h2>
    <a href="add_product.php" class="btn btn-primary" style="background-color: #007bff; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; margin-bottom: 10px;">Add New Product</a>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left; margin-bottom: 20px; color: black;">
        <thead style="background-color: #f9f9f9;">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= $product['id'] ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['price']) ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $product['id'] ?>" style="color: black; text-decoration: none;">Edit</a>
                        <a href="delete_product.php?id=<?= $product['id'] ?>" style="color: black; text-decoration: none;" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="container" style="width: 90%; margin: 20px auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
        <h1 style="text-align: center; color: black;">Admin Dashboard</h1>

        <!-- Employee Registration Section -->
        <h2 style="border-bottom: 2px solid #ccc; padding: 5px; color: black;">Register New Employee</h2>
        <?php if (isset($message)) {
            echo "<p style='color: green;'>$message</p>";
        } ?>
        <form method="post" action="" style="margin-bottom: 20px;">
            <label for="name" style="color: black;">Name:</label>
            <input type="text" name="name" id="name" required style="padding: 5px; border-radius: 4px; width: 100%; margin-bottom: 10px;"><br>

            <label for="email" style="color: black;">Email:</label>
            <input type="email" name="email" id="email" required style="padding: 5px; border-radius: 4px; width: 100%; margin-bottom: 10px;"><br>

            <label for="password" style="color: black;">Password:</label>
            <input type="password" name="password" id="password" required style="padding: 5px; border-radius: 4px; width: 100%; margin-bottom: 10px;"><br>

            <label for="role" style="color: black;">Role:</label>
            <select name="role" id="role" required style="padding: 5px; border-radius: 4px; width: 100%; margin-bottom: 10px;">
                <option value="admin">Admin</option>
                <option value="employee">Employee</option>
                <option value="user">User</option>
            </select><br>

            <button type="submit" style="background-color: #007bff; color: white; padding: 10px 20px; border-radius: 4px;">Register Employee</button>
        </form>
        <!-- Order Management Section -->
        <h2 style="border-bottom: 2px solid #ccc; padding: 5px; color: black;">View Orders</h2>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left; margin-bottom: 20px; color: black;">
            <thead style="background-color: #f9f9f9;">
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
                        <td><?= htmlspecialchars($order['total']) ?></td>
                        <td><?= htmlspecialchars($order['order_date']) ?></td>
                        <td><a href="view_order.php?id=<?= $order['id'] ?>" style="color: black; text-decoration: none;">View Details</a></td>
                        <td>
                            <?php if ($order['status'] === "pending"): ?>
                                Pending <a href="update_order.php?id=<?= $order['id'] ?>&status=preparing" style="background-color: #28a745; color:white; border: none; padding: 5px 10px; border-radius: 4px;">Start Preparing</a>
                            <?php elseif ($order['status'] === "preparing"): ?>
                                Preparing <a href="update_order.php?id=<?= $order['id'] ?>&status=ready" style="background-color: #2311a1; color:white; border: none; padding: 5px 10px; border-radius: 4px;">Mark As Ready</a>
                            <?php elseif ($order['status'] === "ready"): ?>
                                Ready For Pickup
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- User Role Management Section -->
        <h2 style="border-bottom: 2px solid #ccc; padding: 5px; color: black;">Manage User Roles</h2>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left; margin-bottom: 20px; color: black;">
            <thead style="background-color: #f9f9f9;">
                <tr>
                    <th>User ID</th>
                    <th>Email</th>
                    <th>Current Role</th>
                    <th>Change Role</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <select name="role" style="padding: 5px; border-radius: 4px;">
                                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="employee" <?= $user['role'] == 'employee' ? 'selected' : '' ?>>Employee</option>
                                    <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                                </select>
                                <button type="submit" style="background-color: #007bff; color: black; border: none; padding: 5px 10px; border-radius: 4px;">Update Role</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Leave Requests Management Section -->
        <h2 style="border-bottom: 2px solid #ccc; padding: 5px; color: black;">Manage Leave Requests</h2>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left; color: black;">
            <thead style="background-color: #f9f9f9;">
                <tr>
                    <th>Leave ID</th>
                    <th>User Email</th>
                    <th>Leave Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leave_requests as $leave): ?>
                    <tr>
                        <td><?= $leave['id'] ?></td>
                        <td><?= htmlspecialchars($leave['email']) ?></td>
                        <td><?= htmlspecialchars($leave['leave_date']) ?></td>
                        <td><?= htmlspecialchars($leave['reason']) ?></td>
                        <td><?= ucfirst($leave['status']) ?></td>
                        <?php if ($leave['status'] === "pending"): ?>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="leave_id" value="<?= $leave['id'] ?>">
                                    <button type="submit" name="action" value="approved" style="color: black; background-color: #28a745; border: none; padding: 5px 10px; border-radius: 4px;">Approve</button>
                                    <button type="submit" name="action" value="rejected" style="color: black; background-color: #dc3545; border: none; padding: 5px 10px; border-radius: 4px;">Reject</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Messages Section -->
        <h2 style="border-bottom: 2px solid #ccc; padding: 5px; color: black;">Employee Contact Messages</h2>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left; color: black;">
            <thead style="background-color: #f9f9f9;">
                <tr>
                    <th>Message ID</th>
                    <th>Employee ID</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message): ?>
                    <tr>
                        <td><?= $message['id'] ?></td>
                        <td><?= $message['user_id'] ?></td>
                        <td><?= htmlspecialchars($message['message']) ?></td>
                        <td><?= htmlspecialchars($message['submitted_at']) ?></td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Reviews Section -->
        <h2 style="border-bottom: 2px solid #ccc; padding: 5px; color: black;">Reviews</h2>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left; color: black;">
            <thead style="background-color: #f9f9f9;">
                <tr>
                    <th>Review ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Review</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?= $review['id'] ?></td>
                        <td><?= htmlspecialchars($review['name']) ?></td>
                        <td><?= htmlspecialchars($review['email']) ?></td>
                        <td><?= htmlspecialchars($review['review']) ?></td>
                        <td><?= htmlspecialchars($review['created_at']) ?></td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include 'footer.php'; ?>