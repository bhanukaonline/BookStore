<?php
session_start();

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // Process payment (simulated)
    $payment_status = "Success"; // Simulated payment success

    if ($payment_status == "Success") {
        // Clear the cart or mark items as purchased
        // Redirect to a confirmation page
        header("Location: confirmation.php");
        exit();
    } else {
        echo "Payment failed. Please try again.";
    }
}
?>
