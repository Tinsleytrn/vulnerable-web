<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
require_once "database.php";
// Fetch products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $products = [];
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            padding: 20px 0;
            background-color: #a18b89;
            color: white;
        }

        .header h1 {
            margin: 0;
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .product {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 20px;
            text-align: center;
            width: 30%;
        }

        .product img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }

        .product h2 {
            font-size: 1.5em;
            margin: 10px 0;
        }

        .product p {
            color: #555;
            font-size: 1em;
        }

        .product .price {
            color: #beb2b1;
            font-size: 1.2em;
            font-weight: bold;
            margin: 10px 0;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #a18b89;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;

        }

        h1 {
            font-size: 2.5em;
            font-weight: bold;
            margin: 20px 0;
        }

        p {
            font-size: 1em;
            color: #333;
            line-height: 1.5;
        }

        .back-button {
            background-color: #827776;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
            position: absolute;
            left: 20px;
        }
        .log-out{
            position: center;
            left: 100px; 
        }
        .log-out-btn{
            background-color: #e74c3c;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 4px;
            margin: 4px 2px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="header">
        <button class="back-button" onclick="history.back()">Back</button>
        <h1>Welcome to the Shop</h1>
        <p>Hi, <?php echo htmlspecialchars($_SESSION["user"]); ?>!</p>
        <form action="logout.php" method="post" class="log-out">
                <button type="submit" class="log-out-btn">Log Out</button>
            </form>
    </div>
    <div class="container">
        <div class="products">
            
            <?php foreach ($products as $product) : ?>
                <div class="product">
                    <!-- adding htmlspecialchars to prevent XSS -->
                    <h2><?php echo ($product['name']); ?></h2>
                    <img src="uploads/<?php echo ($product['image']); ?>" alt="<?php echo ($product['name']); ?>">
                    <p><?php echo ($product['description']); ?></p>
                    <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                    <form action="" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="footer">
        &copy; 2024 Shop. All Rights Reserved.
    </div>
</body>

</html>