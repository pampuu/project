<?php
session_start();
include 'header.php';  // Include the header
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Cheese Factory</title>
    <style>
        /* General Styling */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', sans-serif;
            background-color: #fffbe6; /* Light background for contrast */
        }

        /* About Section Styling */
        .about {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh; /* Full height for better layout */
            padding: 40px 20px;
        }

        .about-content {
            max-width: 800px;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        }

        .about-content h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #E1AD01; /* Accent color */
        }

        .about-content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            line-height: 1.6;
            color: #333; /* Darker text for readability */
        }

        .about-content img {
            width: 100%;
            max-width: 400px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Image shadow */
        }

        /* Footer Styling */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 15px 0;
            width: 100%;
            position: fixed;
            bottom: 0;
        }

        footer a {
            color: #E1AD01;
            text-decoration: none;
        }

        footer a:hover {
            color: white;
        }

        /* Media Query for Responsive Design */
        @media (max-width: 768px) {
            .about-content {
                padding: 20px;
            }

            .about-content h2 {
                font-size: 2rem;
            }

            .about-content p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<main>
    <section class="about">
        <div class="about-content">
            <h2>About Cheese Steak Factory</h2>
            <p>At the Cheese Steak Factory, we pride ourselves on serving the best cheesesteaks, burgers, and American classics. 
               Learn more about our passion for great food and our journey from humble beginnings to a favorite local spot.</p>
            <img src="cheese.jpg" alt="Cheese Steak Factory">
        </div>
    </section>
</main>

<!-- Footer -->
<footer>
    <p>&copy; 2024 The Cheese Steak Factory. All Rights Reserved.</p>
    <p>Contact us at <a href="mailto:info@cheesefactory.com">info@cheesefactory.com</a></p>
</footer>

</body>
</html>

<?php
include 'footer.php';  // Include the footer if needed for dynamic pages
?>
