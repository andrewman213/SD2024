<?php
// This should be in a separate config.php file:
$servername = "localhost";
$username = "root"; // Your DB username
$password = ""; // Your DB password
$dbname = "reddit_clone"; // Your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

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
  $email = test_input($_POST["email"]);

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
      echo "New record created successfully";
    } else {
      echo "Error: " . $insert->error;
    }
    $insert->close();
  } else {
    // User exists
    echo "User already exists";
  }
}

$conn->close();
?>
