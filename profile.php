<?php
session_start();
 // Log in by user cookie session
if (!isset($_COOKIE["user_data"])){
    header("Location: login.php");
    exit();
}
$user_data = unserialize(base64_decode($_COOKIE["user_data"],true ));
$username = $user_data["username"];
$user_id = $user_data["user_id"];

//  Insecure Deserialization to change to another user profile
require_once "database.php";
if (isset($_GET["user_id"])) {
    $user_id = $_GET["user_id"];
    $sql = "SELECT username, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists in the database
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $email = $row['email'];
    } else {
     // If user does not exist in the database, log them out
    session_destroy();
    setcookie("user_data", "", time() - 3600, "/");
    header("Location: login.php");
    exit();
    }
}

// Prevent Insecure Deserialization Code
//  require_once "database.php";
// $user_id = $_SESSION["user_id"]; // Get the user ID from the session
// $sql = "SELECT username, email FROM users WHERE id = ?";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param("i", $user_id);
// $stmt->execute();
// $result = $stmt->get_result();

// // Check if the user exists in the database
// if ($result->num_rows > 0) {
//     $row = $result->fetch_assoc();
//     $username = $row['username'];
//     $email = $row['email'];
// } else {
//     // If user does not exist in the database, log them out
//     session_destroy();
//     setcookie("user_data", "", time() - 3600, "/");
//     header("Location: login.php");
//     exit();
// }


// //  Vulnerable File upload code:
if (isset($_POST["upload"])) {
    // Check if file was uploaded without errors
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        // Check file size (arbitrarily large limit for demonstration)
        if ($_FILES["file"]["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            exit();
        }
        // Allow any file type (no proper validation)
        $uploadOk = 1;
        // Attempt to move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            echo "The file " . basename($_FILES["file"]["name"]) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "No file uploaded.";
    }
}

// File upload Secure code: 
// if (isset($_POST["upload"])) {
//         if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
//             $target_dir = "images/";
//             $uploadOk = 1;
//             $imageFileType = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
//             $check = getimagesize($_FILES["file"]["tmp_name"]);

//             // Check if image file is an actual image or fake image
//             if ($check !== false) {
//                 echo "File is an image - " . $check["mime"] . ".";
//                 $uploadOk = 1;
//             } else {
//                 echo "File is not an image.";
//                 $uploadOk = 0;
//             }
//             // Check file size
//             if ($_FILES["file"]["size"] > 500000) {
//                 echo "Sorry, your file is too large.";
//                 $uploadOk = 0;
//             }
//             // Allow certain file formats
//             $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
//             if (!in_array($imageFileType, $allowed_types)) {
//                 echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
//                 $uploadOk = 0;
//             }
//             // Rename the file to avoid using user-supplied names
//             $new_file_name = uniqid('img_', true) . '.' . $imageFileType;
//             $target_file = $target_dir . $new_file_name;
//             // Check if $uploadOk is set to 0 by an error
//             if ($uploadOk == 0) {
//                 echo "Sorry, your file was not uploaded.";
//             } else {
//                 // if everything is ok, try to upload file
//                 if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
//                     echo "The file " . basename($_FILES["file"]["name"])  . " has been uploaded.";
//                 } else {
//                     echo "Sorry, there was an error uploading your file.";
//                 }
//             }
//         } else {
//             echo "No file uploaded.";
//         }
//     }

//  Email Update code
if (isset($_POST["update_email"])) {
    if (isset($_POST["email"])) {
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Assume $conn is your database connection
            require_once "database.php";
            $stmt = $conn->prepare("UPDATE users SET email = ? WHERE username = ?");
            $stmt->bind_param("ss", $email, $username);
            if ($stmt->execute()) {
                echo "Email updated successfully.";
            } else {
                echo "Error updating email.";
            }
            $stmt->close();
        } else {
            echo "Invalid email format.";
        }
    } else {
        echo "Email is required.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Open+Sans:600'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>User Dashboard</title>
</head>

<body>
    <div class="container">
        <h1 class="mt-5">Welcome, <?php echo htmlspecialchars($username); ?></h1> <!-- Display username -->
        <h2>Upload Profile Picture</h2>
        <form action="" method="post" enctype="multipart/form-data" class="log-out-btn">
            <div class="mb-3">
                <input type="file" name="file" id="file" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary" name="upload">Upload</button>
        </form>
        <?php if (isset($target_file)) : ?>
            <div class="mt-3">
                <p>Uploaded file:</p>
                <img src="<?php echo htmlspecialchars($target_file); ?>" alt="Profile Picture" class="img-thumbnail" style="max-width: 200px;">
            </div>
        <?php endif; ?>
        <br>
        <h3>Update Email</h3>
        <form action="" method="post" class="log-out-btn">
            <div class="mb-3">
                <p><?php echo htmlspecialchars($email); ?></p>
                <input type="hidden" name="user_id" value="">
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your new email">
            </div>
            <button type="submit" class="btn btn-primary" name="update_email">Update Email</button>
        </form>
      <input type="hidden" name="user_id" value="<?php echo $_SESSION["user_id"] ?>">

        <div class="button-container">
            <!-- Home button -->
            <form action="product.php" method="post" class="log-out-btn">
                <button type="submit" class="btn btn-success">Go To Shop</button>
            </form>

            <!-- Logout button -->
            <form action="logout.php" method="post" class="log-out-btn">
                <button type="submit" class="btn btn-danger">Log Out</button>
            </form>
        </div>
    </div>
</body>

</html>