<?php
session_start();

// Include database connection code here
$serverName = "DESKTOP-O1U5IE6"; // Change to your server name
$connectionOptions = array("Database" => "OnlineBookstoreDB", "Uid" => "sa", "PWD" => "c3@admin"); // Replace username and password

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

if (isset($_POST['cart_id'])) {
    $cart_id = $_POST['cart_id'];

    $delete_query = "DELETE FROM Cart WHERE cart_id = $cart_id";
    $delete_stmt = sqlsrv_query($conn, $delete_query);

    if ($delete_stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    echo "Book removed from cart successfully.";
}

sqlsrv_close($conn);
?>
