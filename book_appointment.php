<?php
require_once('./connection.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo '<script>alert("You are not logged in. Please login first.");</script>';
    header('Location: login.php');
    exit();
} else {
    $user_id = $_SESSION['user_id'];
    $doctor = $_POST['doctorId'];
    $charge = $_POST['charge'];
    $appointment_date = $_POST['appointmentDate'];
    $appointment_time = $_POST['appointmentTime'];
    $formatted_appointment_date = date('j F, Y', strtotime($appointment_date));
    $formatted_appointment_time = date('h:i A', strtotime($appointment_time));

    try {
        // Connect to the database
        $conn = Teledoc::connect();

        // Prepare the query to select the username associated with the user ID
        $usernameQuery = "SELECT name FROM info WHERE user_type = 2 AND id=:user_id";

        // Prepare and execute the statement
        $usernameStmt = $conn->prepare($usernameQuery);
        $usernameStmt->bindParam(':user_id', $user_id);
        $usernameStmt->execute();

        // Fetch the username
        $row = $usernameStmt->fetch(PDO::FETCH_ASSOC);
        $username = $row['name'];

    } catch (PDOException $e) {
        // Handle any errors
        echo 'Error: ' . $e->getMessage();
    }

    try {
        // Connect to the database
        $conn = Teledoc::connect();

        $doctorQuery = "SELECT name FROM doctor WHERE IndexNumber=:doctor";
        $doctorStmt = $conn->prepare($doctorQuery);
        $doctorStmt->bindParam(':doctor', $doctor);
        $doctorStmt->execute();
        $row = $doctorStmt->fetch(PDO::FETCH_ASSOC);
        $doctor_name = $row['name'];
    } catch (PDOException $e) {
        // Handle any errors
        echo 'Error: ' . $e->getMessage();
    }

    try {
        // Connect to the database
        $conn = Teledoc::connect();

        // Prepare the query to insert the appointment into the database
        $insertAppointmentQuery = "INSERT INTO doctor_availablity (user_id, doc_id, date, appointment_time, cost) VALUES (:user_id, :doc_id, :date, :appointment_time, :cost)";
        $insertAppointmentStmt = $conn->prepare($insertAppointmentQuery);
        $insertAppointmentStmt->bindParam(':user_id', $user_id);
        $insertAppointmentStmt->bindParam(':doc_id', $doctor);
        $insertAppointmentStmt->bindParam(':date', $appointment_date);
        $insertAppointmentStmt->bindParam(':appointment_time', $appointment_time);
        $insertAppointmentStmt->bindParam(':cost', $charge);

        // Execute the statement
        $insertAppointmentStmt->execute();

        // Set session variables for payment details
        $_SESSION['doc_id'] = $doctor;
        $_SESSION['amount'] = $charge;
        $_SESSION['transaction_id'] = uniqid();

        // Redirect to the payment success page
        header('Location: payment_success.php');
        exit();

    } catch (PDOException $e) {
        // Handle any errors
        echo 'Error: ' . $e->getMessage();
    }
}
?>
