<?php
include 'db.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form input and sanitize it
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);  // Hash the password

    // Check if email already exists
    $checkEmail = "SELECT * FROM users WHERE email = :email";
    $checkStmt = $conn->prepare($checkEmail);
    $checkStmt->bindParam(':email', $email);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() > 0) {
        $message = "Email already registered! Please try a different email.";
    } else {
        // Prepare SQL query using prepared statements
        $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, 'user')";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            $message = "Registration successful! <a href='login.php'>Login here</a>";
        } else {
            $message = "Error: Could not complete registration. Please try again.";
        }
    }
}
?>

<div class="form-container">
    <h1>Register</h1>
    <form method="post" action="register.php">
        <input type="text" name="username" required placeholder="Username">
        <input type="email" name="email" required placeholder="Email">
        <input type="password" name="password" required placeholder="Password">
        <button type="submit">Register</button>
        <div style="margin-top: 10px;">Already have an account? <a href="login.php">Login</a></div>
    </form>
    <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>
</div>

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
    .form-container input {
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
    .message {
        margin-top: 20px;
        font-size: 16px;
        color: #dc3545; /* Error color */
    }
    .message a {
        color: #28a745;
    }
</style>

<?php include 'footer.php'; ?>
