<?php
session_start();
include 'config.php';
include 'header.php';
include 'db.php';
?>
<style>
    .hero {
        text-align: center;
        padding: 100px 20px;
        background-image: url('cheesesteak.jpg');
        background-size: cover;
        background-position: center;
        color: white;
        position: relative;
    }

    .hero .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
    }

    .hero h2 {
        position: relative;
        z-index: 2;
        font-size: 3rem;
        margin-bottom: 20px;
    }

    .hero p {
        position: relative;
        z-index: 2;
        font-size: 1.5rem;
        margin-bottom: 40px;
    }

    .hero a {
        position: relative;
        z-index: 2;
        background-color: #E1AD01;
        color: white;
        padding: 15px 30px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 1.2rem;
        transition: background-color 0.3s ease;
    }

    .hero a:hover {
        background-color: #d89500;
    }

    .catalogue {
        padding: 50px;
        text-align: center;
    }

    .catalogue h2 {
        font-size: 2.5rem;
        margin-bottom: 40px;
        color: #333;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .product {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }

    .product:hover {
        transform: scale(1.05);
    }

    .product img {
        max-width: 100%;
        border-radius: 10px;
    }

    .product h2 {
        font-size: 1.5rem;
        margin: 10px 0;
        color: #333;
    }

    .product p {
        color: #666;
    }

    .product .btn {
        display: inline-block;
        margin-top: 15px;
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        border-radius: 5px;
        text-decoration: none;
    }

    .product .btn:hover {
        background-color: #218838;
    }
</style>

<main>
    <section class="hero">
        <div class="overlay"></div>
        <h2>Welcome to Cheese Factory</h2>
        <p>Your go-to spot for mouth-watering cheesesteaks, burgers, and more!</p>
        <a href="menu.php" class="button">Explore Our Menu</a>
    </section>

    <section class="catalogue">
        <h2>Featured Products</h2>
        <div class="product-grid">
            <?php
            $sql = "SELECT * FROM products LIMIT 3";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result && count($result) > 0) {
                foreach ($result as $row) {
                    echo "<div class='product'>";
                    echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
                    echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
                    echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                    echo "<p>Price: $" . htmlspecialchars($row['price']) . "</p>";
                    echo "<a href='add_to_cart.php?id=" . htmlspecialchars($row['id']) . "' class='btn'>Add to Cart</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>No products found.</p>";
            }
            ?>
        </div>
    </section>

    <section class="testimonials">
        <h2>What Our Customers Are Saying</h2>
        <div class="testimonial">
            <p>"The best cheesesteak I’ve ever had! The meat was tender and flavorful, and the cheese was perfectly melted."</p>
            <p class="author">- Jake, Melbourne</p>
        </div>
        <div class="testimonial">
            <p>"I love their burgers! Great taste, and the fries are always crispy. Highly recommend."</p>
            <p class="author">- Emma, Sydney</p>
        </div>
    </section>

    
     <!-- Map Section -->
    <section class="map-section">
        <h2>Find Us Here</h2>
        <p>Visit our Cheese Factory location!</p>
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434509052!2d144.95373531550467!3d-37.816279742014795!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0xf577c3d3b6df6c0!2sFederation%20Square!5e0!3m2!1sen!2sau!4v1614709283497!5m2!1sen!2sau"
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </section>

    <section class="call-to-action">
        <h2>Join Our Foodie Community!</h2>
        <p>Subscribe to our newsletter for exclusive deals, new menu items, and events!</p>
        <a href="login.php" class="button">Sign Up Now</a>
    </section>
</main>

</main>

<?php
include 'footer.php';
?>
