<?php
// Include the database configuration file
require_once 'config.php';

// Function to sanitize data to prevent SQL injection
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Assign and sanitize POST data
  $username = test_input($_POST["username"]);
  $password = test_input($_POST["password"]);
  $confirm_password = test_input($_POST["confirm_password"]);
  $email = test_input($_POST["email"]);

  // Check if passwords match
  if ($password === $confirm_password) {
      // Check if user exists
      $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $stmt->store_result();
      
      if($stmt->num_rows == 0) {
          // No user exists, proceed with registration
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
          // User exists
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
</head>
<body>
    <form action="registration.php" method="post">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        Confirm Password: <input type="password" name="confirm_password" required><br>
        Email: <input type="email" name="email" required><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>
