<link rel="stylesheet" href="registration.css">



<?php
require_once 'config.php';

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Assign and sanitize POST data
  $username = test_input($_POST["username"]);
  $password = test_input($_POST["password"]);
  $confirm_password = test_input($_POST["confirm_password"]);
  $email = test_input($_POST["email"]);

  // Check if passwords match
  if ($password === $confirm_password) {
      $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $stmt->store_result();
      
      if($stmt->num_rows == 0) {
          $stmt->close();
          $insert = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
          $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password before saving to the database
          $insert->bind_param("sss", $username, $hashed_password, $email);
          $insert->execute();
          
          if ($insert->affected_rows > 0) {
              // Redirect to login page after successful registration
              header("Location: login.php");
              exit();
          } else {
              echo "Error: " . $insert->error;
          }
          $insert->close();
      } else {
          echo "User already exists";
      }
  } else {
      echo "Passwords do not match.";
  }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="registration.css"> 
</head>
<body>
    <div class="registration-wrapper">
        <h2>Registration</h2>
        <form action="registration.php" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <input type="submit" class="btn" value="Register">
        </form>
    </div>
</body>
</html>
