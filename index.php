<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Search</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
  <h1>Search for Books</h1>
  <?php
  session_start();
  if (isset($_SESSION['username'])) {
    echo "<p>Welcome, " . $_SESSION['username'] . "!</p>";
  } else {
    echo "<p>Please <a href='login.html'>login</a> to search for books.</p>";
  }
  ?>
  <form action="search.php" method="GET" class="search-form">
    <div class="form-group">
      <label for="title">Title:</label>
      <input type="text" id="title" name="title" placeholder="Enter book title">
    </div>
    <div class="form-group">
      <label for="author">Author:</label>
      <input type="text" id="author" name="author" placeholder="Enter author name">
    </div>
    <div class="form-group">
      <label for="category">Category:</label>
      <select id="category" name="category">
        <option value="">Select category</option>
        <option value="fiction">Fiction</option>
        <option value="non-fiction">Non-Fiction</option>
        <option value="fantasy">Fantasy</option>
        <!-- Add more categories as needed -->
      </select>
    </div>
    <button type="submit" class="btn-search">Search</button>
  </form>
</div>
</body>
</html>
