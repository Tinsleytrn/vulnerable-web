<?php
session_start();
require_once "database.php";
//Prevent IDOR vulnerability
// $username = $_SESSION["user"];
// $sql = "SELECT role FROM users WHERE username = ?";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param("s", $username);
// $stmt->execute();
// $result = $stmt->get_result();
// if ($result->num_rows > 0) {
//     $row = $result->fetch_assoc();
//     if ($row['role'] !== 'admin') {
//         header("Location: unauthorized.php");
//         exit();
//     }
// }

// Check if user ID is provided in the request
if (isset($_GET['id'])) {
    $userID = $_GET['id'];
    // Prepare SQL statement to delete the user
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect back to admin dashboard with success message
        header("Location: admin.php?message=User+deleted+successfully.");
        exit();
    } else {
        // Redirect back to admin dashboard with error message
        header("Location: admin.php?error=Error+deleting+user.");
        exit();
    }
} else {
    // If user ID is not provided, redirect back to admin dashboard
    header("Location: admin.php?error=Invalid+request.");
    exit();
}


