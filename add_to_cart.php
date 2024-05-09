<?php
session_start();

// Include database connection code here
$serverName = "DESKTOP-O1U5IE6"; // Change to your server name
$connectionOptions = array("Database" => "OnlineBookstoreDB", "Uid" => "sa", "PWD" => "c3@admin"); // Replace username and password

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

if (isset($_SESSION['username']) && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
    $username = $_SESSION['username'];

    // Fetch user ID based on username
    $user_query = "SELECT user_id FROM Users WHERE username = '$username'";
    $user_stmt = sqlsrv_query($conn, $user_query);

    if ($user_stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_has_rows($user_stmt)) {
        $user_row = sqlsrv_fetch_array($user_stmt, SQLSRV_FETCH_ASSOC);
        $user_id = $user_row['user_id'];

        // Check if the book is already in the cart
        $check_query = "SELECT * FROM Cart WHERE user_id = $user_id AND book_id = $book_id";
        $check_stmt = sqlsrv_query($conn, $check_query);

        if ($check_stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        if (sqlsrv_has_rows($check_stmt)) {
            // Update quantity if the book is already in the cart
            $update_query = "UPDATE Cart SET quantity = quantity + 1 WHERE user_id = $user_id AND book_id = $book_id";
            $update_stmt = sqlsrv_query($conn, $update_query);

            if ($update_stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
        } else {
            // Insert new entry if the book is not in the cart
            $insert_query = "INSERT INTO Cart (user_id, book_id, quantity) VALUES ($user_id, $book_id, 1)";
            $insert_stmt = sqlsrv_query($conn, $insert_query);

            if ($insert_stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
        }

        echo "Book added to cart successfully.";
    }

    sqlsrv_free_stmt($user_stmt);
    sqlsrv_free_stmt($check_stmt);
}

sqlsrv_close($conn);
?>
