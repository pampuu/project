<?php
// Start session and include database connection
session_start();
include 'db.php';  // Database connection

// Check if the user is logged in and is an employee
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header('Location: login.php');
    exit();
}

// Check if the form is submitted for Check In, Check Out, or Leave Request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $date = date('Y-m-d');
    $time = date('H:i:s');

    if (isset($_POST['check_in'])) {
        // Check if the employee has already checked in today
        $query = "SELECT * FROM attendance WHERE user_id = :user_id AND date = :date";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            // Insert a new check-in record
            $insertQuery = "INSERT INTO attendance (user_id, date, check_in_time) VALUES (:user_id, :date, :check_in_time)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $insertStmt->bindParam(':date', $date, PDO::PARAM_STR);
            $insertStmt->bindParam(':check_in_time', $time, PDO::PARAM_STR);
            $insertStmt->execute();
            $message = "Checked in successfully!";
        } else {
            $message = "You have already checked in today.";
        }
    }

    if (isset($_POST['check_out'])) {
        // Update the check-out time for today's attendance
        $updateQuery = "UPDATE attendance SET check_out_time = :check_out_time WHERE user_id = :user_id AND date = :date";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bindParam(':check_out_time', $time, PDO::PARAM_STR);
        $updateStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $updateStmt->bindParam(':date', $date, PDO::PARAM_STR);
        $updateStmt->execute();
        $message = "Checked out successfully!";
    }

    // Handle leave request
    if (isset($_POST['request_leave'])) {
        $leave_date = $_POST['leave_date'];
        $reason = $_POST['reason'];

        $leaveQuery = "INSERT INTO leave_requests (user_id, leave_date, reason) VALUES (:user_id, :leave_date, :reason)";
        $leaveStmt = $conn->prepare($leaveQuery);
        $leaveStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $leaveStmt->bindParam(':leave_date', $leave_date, PDO::PARAM_STR);
        $leaveStmt->bindParam(':reason', $reason, PDO::PARAM_STR);
        $leaveStmt->execute();

        $message = "Leave request submitted successfully!";
    }

    // Handle contact admin request
    if (isset($_POST['contact_admin'])) {
        $message_to_admin = $_POST['message'];
        $query = "INSERT INTO employee_contact_messages (user_id, message) VALUES (:user_id, :message)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':message', $message_to_admin, PDO::PARAM_STR);
        $stmt->execute();

        // You can save this message in the database or send it via email.
        // For simplicity, we'll just display it as a confirmation.
        $message = "Your message has been sent to the admin!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <style>
        .dashboard-container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            text-align: center;
        }

        button {
            padding: 12px 20px;
            font-size: 18px;
            margin: 10px;
            cursor: pointer;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
    </style>
</head>

<body>

    <div class="dashboard-container">
        <h1>Employee Dashboard</h1>

        <!-- Display message for check-in, check-out, leave request, or contact success -->
        <?php if (isset($message)) {
            echo "<p>$message</p>";
        } ?>

        <!-- Check In and Check Out Buttons -->
        <form method="post">
            <button type="submit" name="check_in">Check In</button>
            <button type="submit" name="check_out">Check Out</button>
        </form>

        <!-- Leave Request Form -->
        <h2>Request Leave</h2>
        <form method="post">
            <label for="leave_date">Leave Date:</label>
            <input type="date" name="leave_date" required>

            <label for="reason">Reason for Leave:</label>
            <textarea name="reason" rows="4" required></textarea>

            <button type="submit" name="request_leave">Submit Leave Request</button>
        </form>

        <!-- Contact Admin Form -->
        <h2>Contact Admin</h2>
        <form method="post">
            <label for="message">Message:</label>
            <textarea name="message" rows="4" required></textarea>

            <button type="submit" name="contact_admin">Send Message to Admin</button>
        </form>
    </div>

</body>

</html>