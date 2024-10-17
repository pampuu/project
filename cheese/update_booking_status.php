<?php
include('db.php'); // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = $_POST['booking_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        $query = "UPDATE bookings SET status='Approved' WHERE id=?";
    } elseif ($action == 'reject') {
        $query = "UPDATE bookings SET status='Rejected' WHERE id=?";
    } elseif ($action == 'delete') {
        $query = "DELETE FROM bookings WHERE id=?";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the admin dashboard after updating
    header("Location: admin_dashboard.php");
    exit;
}
?>
