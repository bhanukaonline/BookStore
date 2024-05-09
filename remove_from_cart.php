<?php


$serverName = "localhost"; // Change to your server name
$connectionOptions = array("Database" => "OnlineBookstoreDB", "Uid" => "username", "PWD" => "password"); // Replace username and password

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user_id = $_SESSION['user_id'];
  $cart_id = $_POST['cart_id'];

  $sql_delete = "DELETE FROM Cart WHERE user_id = $user_id AND cart_id = $cart_id";
  $stmt_delete = sqlsrv_query($conn, $sql_delete);

  if ($stmt_delete === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  // Redirect to cart page
  header("Location: cart.php");
  exit();
}

sqlsrv_close($conn);
?>
