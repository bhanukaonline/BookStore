<?php
session_start();

$serverName = "DESKTOP-O1U5IE6"; // Change to your server name
$connectionOptions = array("Database" => "OnlineBookstoreDB", "Uid" => "sa", "PWD" => "c3@admin"); // Replace username and password


$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  // Validate username and password (you may use stronger validation and encryption methods in a real application)
  $sql = "SELECT * FROM Users WHERE username = '$username' AND password = '$password'";
  $stmt = sqlsrv_query($conn, $sql);

  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

  if ($user) {
    // Authentication successful, set session variables
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_id'] = $user['user_id']; // Assuming 'user_id' is the column name for user ID in your database

    // Redirect to home page or personalized page
    header("Location: index.php"); // Change to appropriate page
    exit();
  } else {
    // Authentication failed, display error message or redirect to login page
    header("Location: login.html");
    exit();
  }
}

sqlsrv_close($conn);
?>
