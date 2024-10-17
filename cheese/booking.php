<?php
session_start();
include 'config.php';
include 'header.php';
include 'db.php';

// Handle form submission
if (isset($_POST['book_table'])) {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $booking_date = $_POST['booking_date'];
    $booking_time = $_POST['booking_time'];
    $guests = $_POST['guests'];
    $status = 'pending'; // Explicitly set status to 'pending'

    // Insert booking into the database using prepared statement
    $sql = "INSERT INTO bookings (name, email, phone, booking_date, booking_time, guests, status)
            VALUES (:name, :email, :phone, :booking_date, :booking_time, :guests, :status)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':booking_date', $booking_date);
    $stmt->bindParam(':booking_time', $booking_time);
    $stmt->bindParam(':guests', $guests);
    $stmt->bindParam(':status', $status); // Bind status

    if ($stmt->execute()) {
        $booking_message = "Your table has been booked successfully!";
    } else {
        $booking_message = "Sorry, there was an error booking your table. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Table</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .table-booking {
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 10px;
            max-width: 600px;
            margin: 40px auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .table-booking h2 {
            text-align: center;
            color: black;
            margin-bottom: 20px;
        }

        .table-booking form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .table-booking label {
            font-weight: bold;
            margin-bottom: 5px;
            color: black;
        }

        .table-booking input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }

        .table-booking input[type="date"],
        .table-booking input[type="time"] {
            max-width: 250px;
        }

        .table-booking button {
            padding: 12px 20px;
            background-color: #28a745;
            color: black;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .table-booking button:hover {
            background-color: #E1AD01;
        }

        /* Success message styling */
        .table-booking p {
            text-align: center;
            font-size: 16px;
            margin-top: 20px;
            color: green;
        }
    </style>
</head>
<body>
    <main>
        <section class="table-booking">
            <h2>Book a Table</h2>
            <form action="" method="POST">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" required>
                
                <label for="booking_date">Date:</label>
                <input type="date" id="booking_date" name="booking_date" required>
                
                <label for="booking_time">Time:</label>
                <input type="time" id="booking_time" name="booking_time" required>
                
                <label for="guests">Number of Guests:</label>
                <input type="number" id="guests" name="guests" min="1" max="20" required>
                
                <button type="submit" name="book_table" class="btn">Book Now</button>
            </form>

            <!-- Success message -->
            <?php if (isset($booking_message)): ?>
                <p><?php echo $booking_message; ?></p>
            <?php endif; ?>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
