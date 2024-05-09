<?php
session_start();

// Include database connection code here
$serverName = "DESKTOP-O1U5IE6"; // Change to your server name
$connectionOptions = array("Database" => "OnlineBookstoreDB", "Uid" => "sa", "PWD" => "c3@admin"); // Replace username and password

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Fetch user ID based on username
    $user_query = "SELECT user_id FROM Users WHERE username = ?";
    $params = array($username);
    $user_stmt = sqlsrv_query($conn, $user_query, $params);

    if ($user_stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_has_rows($user_stmt)) {
        $user_row = sqlsrv_fetch_array($user_stmt, SQLSRV_FETCH_ASSOC);
        $user_id = $user_row['user_id'];

        // Fetch cart items for the user
        $cart_query = "SELECT Cart.cart_id, Books.title, Books.price, Cart.quantity, (Books.price * Cart.quantity) AS total_price 
                       FROM Cart 
                       INNER JOIN Books ON Cart.book_id = Books.book_id 
                       WHERE Cart.user_id = ?";
        $params = array($user_id);
        $cart_stmt = sqlsrv_query($conn, $cart_query, $params);

        if ($cart_stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        echo "<h2>Shopping Cart</h2>";
        echo "<table>";
        echo "<tr><th>Title</th><th>Price</th><th>Quantity</th><th>Total Price</th><th>Action</th></tr>";
        while ($cart_row = sqlsrv_fetch_array($cart_stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $cart_row['title'] . "</td>";
            echo "<td>$" . $cart_row['price'] . "</td>";
            echo "<td>" . $cart_row['quantity'] . "</td>";
            echo "<td>$" . $cart_row['total_price'] . "</td>";
            echo "<td><button onclick='removeFromCart(" . $cart_row['cart_id'] . ")'>Remove</button></td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    sqlsrv_free_stmt($user_stmt);
    sqlsrv_free_stmt($cart_stmt);
}

sqlsrv_close($conn);
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function removeFromCart(cartId) {
    if (confirm("Are you sure you want to remove this item from the cart?")) {
        $.ajax({
            url: 'remove_from_cart.php',
            type: 'POST',
            data: { cart_id: cartId },
            success: function(response) {
                alert(response); // Display success message or handle response as needed
                location.reload(); // Refresh the page after successful removal
            },
            error: function(xhr, status, error) {
                alert('Error removing item from cart.');
                console.log(xhr.responseText);
            }
        });
    }
}
</script>
