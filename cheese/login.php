<?php
// Start session at the beginning
session_start();
include 'db.php';  // Database connection
include 'header.php';  // Include the header for consistent styling

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the email and password from POST
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare a secure query to fetch the user by email using PDO
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    // Fetch the user record
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and verify the password
    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, set session variables
        $_SESSION['user_id'] = $user['id']; // Store the user ID in the session
        $_SESSION['user_email'] = $user['email']; // Optionally store the user's email
        $_SESSION['role'] = $user['role']; // Store the user's role in the session

        // Redirect based on role
        if ($user['role'] == 'admin') {
            header('Location: admin_dashboard.php'); // Redirect admin to the admin dashboard
        } elseif ($user['role'] == 'employee') {
            header('Location: employee_dashboard.php'); // Redirect employee to the employee dashboard
        } else {
            header('Location: index.php'); // Redirect regular user to the user dashboard
        }
        exit(); // Ensure no further code is executed
    } else {
        // If login fails, show an error message
        $error = "Invalid email or password!";
    }
}
?>


<!-- Inline CSS for quick styling -->
<style>
    .form-container {
        width: 100%;
        max-width: 400px;
        margin: 50px auto;
        padding: 30px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .form-container h1 {
        margin-bottom: 20px;
        font-size: 24px;
        color: #333;
    }

    .form-container input[type="email"],
    .form-container input[type="password"] {
        width: 92%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
    }

    .form-container button {
        width: 100%;
        padding: 12px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-container button:hover {
        background-color: #218838;
    }

    .form-container p {
        margin-top: 10px;
        color: red;
    }

    /* Responsive design for smaller screens */
    @media (max-width: 600px) {
        .form-container {
            width: 90%;
        }
    }
</style>

<!-- HTML Login Form -->
<div class="form-container">
    <h1>Login</h1>
    <form method="post" action="login.php">
        <input type="email" name="email" required placeholder="Email">
        <input type="password" name="password" required placeholder="Password">
        <button type="submit">Login</button>
        <div style="margin-top: 10px;">Don't have an account? <a href="register.php">Register</a></div>
    </form>

    <!-- Display error message if login fails -->
    <?php if (isset($error)) {
        echo "<p>$error</p>";
    } ?>
</div>

<?php include 'footer.php'; // Include the footer for consistent styling ?>
