<?php
session_start();

// Include database connection code here
$serverName = "DESKTOP-O1U5IE6"; // Change to your server name
$connectionOptions = array("Database" => "OnlineBookstoreDB", "Uid" => "sa", "PWD" => "c3@admin"); // Replace username and password

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Validate user credentials
$sql = "SELECT * FROM Users WHERE username = '$username' AND password = '$password'";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (sqlsrv_has_rows($stmt)) {
    $_SESSION['username'] = $username;
    header("Location: index.php"); // Redirect to homepage or search page
} else {
    echo "Invalid username or password.";
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
