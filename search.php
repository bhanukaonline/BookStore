<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Search</title>
<link rel="stylesheet" href="styles.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function addToCart(bookId) {
    $.ajax({
        url: 'add_to_cart.php',
        type: 'POST',
        data: { book_id: bookId },
        success: function(response) {
            alert(response); // Display success message or handle response as needed
        },
        error: function(xhr, status, error) {
            alert('Error adding book to cart.');
            console.log(xhr.responseText);
        }
    });
}
</script>
</head>
<body>
<div class="container">
  <h1>Search for Books</h1>
  <!-- Book search results displayed by PHP code -->
</div>
</body>
</html>

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
    echo "<button onclick='addToCart(" . $row['book_id'] . ")'>Add to Cart</button>"; // Pass book_id to addToCart function
    echo "</div>";
}
echo "<a href='view_cart.php'><button>View Cart</button></a>";
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>

