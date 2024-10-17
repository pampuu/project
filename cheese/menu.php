<?php
session_start();
include 'header.php'; 
include 'db.php' ;// Include the header
?>

<main>

<!-- Inline CSS to style the product page -->
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .catalogue {
        max-width: 1200px;
        margin: 50px auto;
        padding: 20px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .catalogue h1 {
        font-size: 36px;
        text-align: center;
        color: #333;
        margin-bottom: 40px;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .product {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .product:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .product img {
        width: 260px;
        height: 185px;
        border-radius: 10px;
    }

    .product h2 {
        font-size: 22px;
        margin: 15px 0;
        color: #333;
    }

    .product p {
        font-size: 16px;
        color: #777;
        margin-bottom: 20px;
    }

    .product .btn {
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .product .btn:hover {
        background-color: #218838;
    }

    /* Responsive adjustments for smaller screens */
    @media (max-width: 600px) {
        .product-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="catalogue">
    <h1>Our Products</h1>
    <div class="product-grid">
        <?php
        // Prepare and execute the SQL query
        $sql = "SELECT * FROM products";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Fetch all the products
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Loop through the products and display them
        if ($products && count($products) > 0) {
            foreach ($products as $row) {
                echo "<div class='product'>";
                echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
                echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
                echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                echo "<p>Price: $" . htmlspecialchars($row['price']) . "</p>";
                echo "<a href='add_to_cart.php?id=" . htmlspecialchars($row['id']) . "' class='btn'>Add to Cart</a>";
                echo "</div>";
            }
        } else {
            // Message if no products are found
            echo "<p>No products available at the moment.</p>";
        }
        ?>
    </div>
</div>
</main>

<?php
include 'footer.php';  // Include the footer
?>
