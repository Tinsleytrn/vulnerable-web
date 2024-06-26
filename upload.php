<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: product.php");
    exit();
}
require_once "database.php";

$username = $_SESSION["user"];
$sql = "SELECT role FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['role'] !== 'admin') {
        header("Location: unauthorized.php");
        exit();
    }
} else {
    // If user does not exist in the database, log them out
    session_destroy();
    header("Location: product.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];

    // Handle file upload
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is an image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        exit();
    }

    // Allow certain file formats
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        exit();
    }

    // Check file size
    if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        exit();
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image = basename($_FILES["image"]["name"]);
        $sql = "INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssds", $name, $description, $price, $image);

        if ($stmt->execute()) {
            echo "Product uploaded successfully.";
            header("Location: product.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upload Product</title>
</head>

<body>
    <style>
        form {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #b4a3a1;
        }

        /* Optional: Style for error messages */
        .error {
            color: #ff0000;
            font-size: 14px;
            margin-top: 5px;
        }

        input[type="submit"] {
            margin-top: 30px;
            background-color: #827776;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .back-button {
            background-color: #827776;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .back-button:hover {
            background-color: #b4a3a1;
        }
        
    </style>
    <h1>Upload Product</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <!--Navigate to product.php to htmlspecialchars-->
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" value="" required><br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>
        <label for="price">Price:</label>
        <input type="text" id="price" name="price" required><br>
        <label for="image">Image:</label>
        <input type="file" id="image" name="image" required><br>
        <input type="submit" value="Upload">
        <button type="button" class="back-button" style="text-align: right;" onclick="history.back()">Back</button>
    </form>
</body>

</html>