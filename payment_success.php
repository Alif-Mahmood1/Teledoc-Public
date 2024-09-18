<?php
require_once('./connection.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    echo '<script>alert("You are not logged in. Please login first.");</script>';
    header('Location: login.php');
    exit();
}

$user_id= $_SESSION['user_id'];
$doc_id = $_SESSION['doc_id'];
$amount = $_SESSION['amount'];
$transaction_id = $_SESSION['transaction_id'];

try {
    // Connect to the database
    $conn = Teledoc::connect();

    // Prepare the query to insert payment details into the database
    $paymentQuery = "INSERT INTO payment_history (user_id, doctor_id, amount, transaction_id) VALUES (:user_id, :doctor_id, :amount, :transaction_id)";
    $paymentStmt = $conn->prepare($paymentQuery);
    $paymentStmt->bindParam(':user_id', $user_id);
    $paymentStmt->bindParam(':doctor_id', $doc_id);
    $paymentStmt->bindParam(':amount', $amount);
    $paymentStmt->bindParam(':transaction_id', $transaction_id);

    // Execute the statement
    $paymentStmt->execute();

    // Clear the session variables
    unset($_SESSION['doc_id']);
    unset($_SESSION['amount']);
    unset($_SESSION['transaction_id']);

} 
catch(PDOException $e)
{
    // Handle any errors
    echo 'Error: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success - TeleDoc</title>
    <link rel="stylesheet" href="css/design.css">
    <link rel="stylesheet" href="css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include the navbar -->
    <?php include 'navbar.php'; ?>
</head>
<body>
<div class="container">
    <h1>Payment Successful</h1>
    <p>Your payment was successful. You can now check your profile for details.</p>
    <a href="profile.php" class="btn btn-primary">Go to Profile</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
