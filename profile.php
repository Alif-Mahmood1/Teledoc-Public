<?php
session_start();
require_once('./connection.php');

if (!isset($_SESSION['user_id'])) {
    echo '<script>alert("You are not logged in. Please login first.");</script>';
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['app_id'])) {
    // Handle appointment cancellation
    try {
        $conn = Teledoc::connect();

        $app_id = $_POST['app_id'];

        // Delete the appointment
        $deleteAppointmentQuery = "DELETE FROM doctor_availablity WHERE app_id = :app_id";
        $deleteAppointmentStmt = $conn->prepare($deleteAppointmentQuery);
        $deleteAppointmentStmt->bindParam(':app_id', $app_id);
        $deleteAppointmentStmt->execute();

        // Redirect to refresh the page
        header('Location: profile.php');
        exit();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

try {
    $conn = Teledoc::connect();

    // Fetch user details
    $query = "SELECT * FROM info WHERE id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch user's appointment details
    $appointmentQuery = "SELECT d.name, da.appointment_time, da.date, da.cost, da.app_id
              FROM doctor_availablity da
              JOIN doctor d ON da.doc_id = d.IndexNumber
              WHERE da.user_id = :user_id";
    $appointmentStmt = $conn->prepare($appointmentQuery);
    $appointmentStmt->bindParam(':user_id', $_SESSION['user_id']);
    $appointmentStmt->execute();

    // Fetch user's payment history
    $paymentQuery = "SELECT p.transaction_id, d.name AS doctor_name, p.amount, p.payment_date
              FROM payment_history p
              JOIN doctor d ON p.doctor_id = d.IndexNumber
              WHERE p.user_id = :user_id";
    $paymentStmt = $conn->prepare($paymentQuery);
    $paymentStmt->bindParam(':user_id', $_SESSION['user_id']);
    $paymentStmt->execute();

} catch(PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - TeleDoc</title>
    <link rel="stylesheet" href="css/design.css">
    <link rel="stylesheet" href="css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include the navbar -->
    <?php include 'navbar.php'; ?>
</head>
<body>
<div class="container">
    <h1>Profile Details</h1>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <h2>Appointments</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Doctor Name</th>
                <th>Appointment Time</th>
                <th>Date</th>
                <th>Cost</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($appointment = $appointmentStmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo htmlspecialchars($appointment['name']); ?></td>
                <td><?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></td>
                <td><?php echo date('Y-m-d', strtotime($appointment['date'])); ?></td>
                <td><?php echo htmlspecialchars($appointment['cost']); ?> tk</td>
                <td>
                    <form action="" method="POST">
                        <input type="hidden" name="app_id" value="<?php echo $appointment['app_id']; ?>">
                        <button type="submit" class="btn btn-danger">Cancel</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <h2>Payment History</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Doctor Name</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($payment = $paymentStmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo htmlspecialchars($payment['transaction_id']); ?></td>
                <td><?php echo htmlspecialchars($payment['doctor_name']); ?></td>
                <td><?php echo htmlspecialchars($payment['amount']); ?> tk</td>
                <td><?php echo date('Y-m-d H:i:s', strtotime($payment['payment_date'])); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
