<?php
// Include database connection code here
$serverName = "DESKTOP-O1U5IE6"; // Change to your server name
$connectionOptions = array("Database" => "OnlineBookstoreDB", "Uid" => "sa", "PWD" => "c3@admin"); // Replace username and password

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

$title = $_GET['title'] ?? '';
$author = $_GET['author'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "SELECT * FROM Books WHERE 1=1";

if (!empty($title)) {
    $sql .= " AND title LIKE '%$title%'";
}

if (!empty($author)) {
    $sql .= " AND author LIKE '%$author%'";
}

if (!empty($category)) {
    $sql .= " AND category = '$category'";
}

$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo "<div>";
    echo "<h3>" . $row['title'] . "</h3>";
    echo "<p>Author: " . $row['author'] . "</p>";
    echo "<p>Price: $" . $row['price'] . "</p>";
    echo "<button>Add to Cart</button>";
    echo "</div>";
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
