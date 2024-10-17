<?php
session_start();
include 'config.php';
include 'header.php';
include 'db.php';

// Handle form submission
if (isset($_POST['submit_review'])) {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $review = $_POST['review'];

    // Insert review into the database using prepared statement
    $sql = "INSERT INTO reviews (name, email, review)
            VALUES (:name, :email, :review)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':review', $review);

    if ($stmt->execute()) {
        $message = "Review Sent Successfully";
    } else {
        $message = "Sorry, there was an error sending the review. Please try again.";
    }
}
?>

<!-- Centered Form -->
<div class="review-container">
    <form action="" method="POST" class="review-form">
        <h1>Leave a Review</h1>
        <input id="name" name="name" type='text' required placeholder='Full Name' />
        <input id="email" name="email" type='email' required placeholder='Email' />
        <textarea required id="review" name="review" rows="8" placeholder='Enter Review Here'></textarea>
        <button type="submit" name="submit_review">Submit Review</button>
    </form>
    
    <!-- Success/Error Message -->
    <?php if (isset($message)): ?>
        <div class="message">
            <p><?php echo $message; ?></p>
        </div>
    <?php endif; ?>
</div>

<!-- Add styling to styles.css -->
<style>
    .review-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh; /* Full height of viewport */
    }

    .review-form {
        background-color: rgba(255, 255, 255, 0.9); /* White background with slight transparency */
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        width: 100%; /* Full width up to max-width */
    }

    .review-form h1 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 2rem;
        color: orange;
    }

    .review-form input,
    .review-form textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1rem;
    }

    .review-form button {
        width: 100%;
        background-color: #28a745;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 5px;
        font-size: 1.2rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .review-form button:hover {
        background-color: #218838;
    }

    /* Styling for the success/error message */
    .message {
        margin-top: 20px;
        font-size: 1.2rem;
        color: white; /* Success message color */
        text-align: center;
    }

    .message.error {
        color: red; /* Error message color */
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .review-form {
            padding: 20px;
        }

        .review-form h1 {
            font-size: 1.5rem;
        }
    }
</style>
