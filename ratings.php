<?php
session_start();
require_once('./connection.php');

interface IRatingService {
    public function getAverageRating($doctorId);
    public function addRating($doctorId, $userId, $rating);
}

class RatingService implements IRatingService {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAverageRating($doctorId) {
        $stmt = $this->db->prepare("SELECT AVG(rating) as average_rating FROM doctor_ratings WHERE doctor_id = :doctorId");
        $stmt->bindParam(':doctorId', $doctorId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addRating($doctorId, $userId, $rating) {
        $stmt = $this->db->prepare("INSERT INTO doctor_ratings (doctor_id, user_id, rating) VALUES (:doctorId, :userId, :rating)");
        $stmt->bindParam(':doctorId', $doctorId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->execute();
    }
}

$ratingService = new RatingService(Teledoc::connect());

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rating'])) {
    $ratingService->addRating($_POST['doctorId'], $_SESSION['user_id'], $_POST['rating']);
    header("Location: ratings.php?doctorId=" . $_POST['doctorId']); // Redirect to avoid resubmission
    exit();
}

$averageRating = null;
if (isset($_GET['doctorId'])) {
    $averageRating = $ratingService->getAverageRating($_GET['doctorId']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Ratings - TeleDoc</title>
    <link rel="stylesheet" href="css/design.css">
    <link rel="stylesheet" href="css/index.css"> <!-- Ensure CSS for index is applied if needed -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Include the navbar -->
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1>Doctor Ratings</h1>
        <?php if ($averageRating): ?>
            <h2>Average Rating: <?= round($averageRating['average_rating'], 1) ?>/5</h2>
        <?php else: ?>
            <h2>No Ratings Yet</h2>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="ratings.php" method="post">
                <input type="hidden" name="doctorId" value="<?= htmlspecialchars($_GET['doctorId']) ?>">
                <label for="rating">Your Rating (1-5):</label>
                <input type="number" name="rating" min="1" max="5" required>
                <button type="submit" class="btn btn-primary">Submit Rating</button>
            </form>
        <?php else: ?>
            <p>Please <a href="login.php">log in</a> to rate.</p>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>