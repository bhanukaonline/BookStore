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

        echo "<h2>Order Summary</h2>";
        echo "<table>";
        echo "<tr><th>Title</th><th>Price</th><th>Quantity</th><th>Total Price</th></tr>";
        $total_amount = 0;
        while ($cart_row = sqlsrv_fetch_array($cart_stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $cart_row['title'] . "</td>";
            echo "<td>$" . $cart_row['price'] . "</td>";
            echo "<td>" . $cart_row['quantity'] . "</td>";
            echo "<td>$" . $cart_row['total_price'] . "</td>";
            echo "</tr>";
            $total_amount += $cart_row['total_price'];
        }
        echo "</table>";

        echo "<h3>Total Amount: $" . $total_amount . "</h3>";

        // Checkout form
        echo "<h2>Checkout</h2>";
        echo "<form action='process_payment.php' method='POST'>";
        echo "<label for='name'>Name:</label>";
        echo "<input type='text' id='name' name='name' required><br>";
        echo "<label for='email'>Email:</label>";
        echo "<input type='email' id='email' name='email' required><br>";
        echo "<label for='address'>Address:</label>";
        echo "<textarea id='address' name='address' required></textarea><br>";
        echo "<input type='submit' value='Proceed to Payment'>";
        echo "</form>";
    }

    sqlsrv_free_stmt($user_stmt);
    sqlsrv_free_stmt($cart_stmt);
}

sqlsrv_close($conn);
?>
