<?php
session_start();
include 'config.php';  // Include your database connection file
include 'header.php';  // Include the header

// Initialize message variable
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message_text = mysqli_real_escape_string($conn, $_POST['message']);

    // Insert data into the database
    $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message_text')";

    if (mysqli_query($conn, $sql)) {
        $message = "Your message has been sent successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<main>
    <section class="contact-form">
        <h2>We'd Love to Hear From You!</h2>
        

        <!-- Display success or error message -->
        <?php if ($message): ?>
            <p class="success-message"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Contact form -->
        <form action="contact.php" method="POST">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <input type="text" name="subject" placeholder="Subject" required>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <button type="submit" class="button">Send Message</button>
        </form>

        <!-- Add a map below the contact form -->
        <div class="map-container">
            <h3>Find Us Here</h3>
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.83543450943!2d144.95592631531645!3d-37.81720997975161!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad65d43cfd8df07%3A0xf5778a7680bff2f5!2sFederation%20Square!5e0!3m2!1sen!2sau!4v1618314843109!5m2!1sen!2sau" 
                width="600" 
                height="450" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </section>
</main>


<?php
include 'footer.php';  // Include the footer
?>
