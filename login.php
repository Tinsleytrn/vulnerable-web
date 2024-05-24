<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: profile.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Open+Sans:600'>
    <link rel="stylesheet" href="style.css">
    <title>Log In</title>
</head>

<body>
    <?php
    //Vulnerable code
      if (isset($_POST["login"])) {
          $username = $_POST["username"];
          $password = $_POST["password"];
          require_once "database.php";
          $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
          // echo $sql;
          $result = mysqli_query($conn, $sql);
          if ($result->num_rows > 0) {
              echo "<p>Login successful!</p>";
              session_start();
              $_SESSION["user"] = $username;
              header("Location: profile.php");
          } else {
              echo "<p>Invalid credentials.</p>";
          }
      }
    // How to prevent SQL: secure code
        // if (isset($_POST["login"])) {
        //      $email = $_POST["email"];
        //      $password = $_POST["password"];
        //      require_once "database.php";
        //      $sql = "SELECT * FROM users WHERE email = '$email'";
        //      $result = mysqli_query($conn, $sql);
        //      $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //      if ($user) {
        //          if (password_verify($password, $user["password"])) {
        //              session_start();
        //              $_SESSION["user"] = $user["username"];
        //              header("Location: profile.php");
        //              die("Something when wrong");
        //          } else {
        //              echo "<div class='alert alert-danger'> Password does not match</div>";
        //          }
        //      } else {
        //          echo "<div class='alert alert-danger'>Email does not match</div>";
        //      }
        //  }
    ?>

    <form action="login.php" method="post">
        <h1>Log In</h1>
        <div class="form-group">
            <input type="text" class="form-control" name="username" placeholder="Username:">
        </div>
        <br>
        <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Password:">
        </div>
        <br>
        <div class="form-btn">
            <input type="submit" class="btn btn-primary" value="submit" name="login">
        </div>
    </form>
    <div>
        <div>
            <p>Don't Have An Account Yet? <a href="registration.php"> Register Here </a></p>
        </div>
    </div>
</body>

</html>