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

$user_id = $_SESSION['user_id'];

$sql_cart = "SELECT c.cart_id, b.title, b.author, b.price, c.quantity FROM Cart c INNER JOIN Books b ON c.book_id = b.book_id WHERE c.user_id = $user_id";
$stmt_cart = sqlsrv_query($conn, $sql_cart);

if ($stmt_cart === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shopping Cart</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
  <h1>Shopping Cart</h1>
  <table>
    <tr>
      <th>Title</th>
      <th>Author</th>
      <th>Price</th>
      <th>Quantity</th>
      <th>Action</th>
    </tr>
    <?php while ($row = sqlsrv_fetch_array($stmt_cart, SQLSRV_FETCH_ASSOC)) { ?>
    <tr>
      <td><?php echo $row['title']; ?></td>
      <td><?php echo $row['author']; ?></td>
      <td>$<?php echo $row['price']; ?></td>
      <td><?php echo $row['quantity']; ?></td>
      <td>
        <form action="remove_from_cart.php" method="POST">
          <input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>">
          <button type="submit">Remove</button>
        </form>
      </td>
    </tr>
    <?php } ?>
  </table>
  <a href="index.html">Continue Shopping</a>
</div>
</body>
</html>

<?php
sqlsrv_free_stmt($stmt_cart);
sqlsrv_close($conn);
?>
