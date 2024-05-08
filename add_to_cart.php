<?php
session_start();

if (!isset($_SESSION['username'])) {
  header("Location: login.html");
  exit();
}

$serverName = "DESKTOP-O1U5IE6"; // Change to your server name
$connectionOptions = array("Database" => "Bookstore", "Uid" => "sa", "PWD" => "c3@admin"); // Replace username and password
$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user_id = $_SESSION['user_id'];
  $book_id = $_POST['book_id'];
  $quantity = $_POST['quantity'];

  // Check if the book is already in the cart
  $sql_check = "SELECT * FROM Cart WHERE user_id = $user_id AND book_id = $book_id";
  $stmt_check = sqlsrv_query($conn, $sql_check);

  if ($stmt_check === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  $row_check = sqlsrv_fetch_array($stmt_check, SQLSRV_FETCH_ASSOC);

  if ($row_check) {
    // Update quantity if book is already in cart
    $quantity += $row_check['quantity'];
    $sql_update = "UPDATE Cart SET quantity = $quantity WHERE user_id = $user_id AND book_id = $book_id";
    $stmt_update = sqlsrv_query($conn, $sql_update);

    if ($stmt_update === false) {
      die(print_r(sqlsrv_errors(), true));
    }
  } else {
    // Insert new item into cart
    $sql_insert = "INSERT INTO Cart (user_id, book_id, quantity) VALUES ($user_id, $book_id, $quantity)";
    $stmt_insert = sqlsrv_query($conn, $sql_insert);

    if ($stmt_insert === false) {
      die(print_r(sqlsrv_errors(), true));
    }
  }

  // Redirect to cart page or home page
  header("Location: cart.php");
  exit();
}

sqlsrv_close($conn);
?>
