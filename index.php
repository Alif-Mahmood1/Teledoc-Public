<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeleDoc - Your Health, Our Priority</title>
    <link rel="stylesheet" href="css/design.css">
    <link rel="stylesheet" href="css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, .jumbotron {
            /* Setting the background for the whole page or just the jumbotron */
            background-image: url('img/1.jpg');
            background-size: cover; /* Cover the entire area of the element */
            background-position: center; /* Center the background image */
            background-repeat: no-repeat; /* Do not repeat the image */
        }
        .jumbotron {
            background-color: rgba(255, 255, 255, 0.8); /* Optional: add a white overlay to improve text readability */
        }
    </style>
</head>
<body>
    <!-- Include the navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Main content -->
    <?php if (isset($_SESSION['username'])): ?>
        <div class="container">
            <div class="jumbotron text-center">
                <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                <p>Your Health, Our Priority</p>
                <p>Explore our services and stay healthy.</p>
                <a class="btn btn-primary" href="profile.php">View Profile</a>
                <p class="mt-4">Book an appointment today!</p>
                <a class="btn btn-success" href="search.php">Book Now</a>
            </div>
        </div>
    <?php else: ?>
        <div class="container">
            <div class="jumbotron text-center">
                <h1>Welcome to TeleDoc</h1>
                <p>Your Health, Our Priority</p>
                <p>Get access to healthcare services from the comfort of your home.</p>
                <a class="btn btn-primary" href="register.php">Register Now</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
