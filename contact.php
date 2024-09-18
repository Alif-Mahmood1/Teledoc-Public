<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - TeleDoc</title>
    <link rel="stylesheet" href="css/design.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Include the navbar -->
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="jumbotron text-center">
            <h1>Contact Us</h1>
            <p>If you have any questions, please don't hesitate to contact us.</p>
            <button id="emailButton" class="btn btn-info" onclick="showEmail()">Email Us</button>
            <p id="emailText" style="display:none;">Email: <a href="mailto:alif.mahmood.cse@ulab.edu.bd">alif.mahmood.cse@ulab.edu.bd</a></p>
            <a id="sendEmail" class="btn btn-primary" style="display:none;" href="https://www.gmail.com" target="_blank">Send Email</a>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        function showEmail() {
            var emailText = document.getElementById('emailText');
            var sendEmailButton = document.getElementById('sendEmail');
            if (emailText.style.display === 'none') {
                emailText.style.display = 'block';
                sendEmailButton.style.display = 'block';
            } else {
                emailText.style.display = 'none';
                sendEmailButton.style.display = 'none';
            }
        }
    </script>
</body>
</html>
