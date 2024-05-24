<?php
include_once 'config.php';
include 'user-nav.php';

// Redirect to login page if user is not logged in
if (!isset($_SESSION['username'])) {
    header("location: userLogin.php");
    exit();
}

// Database connection using $conn from config.php
$mysqli = $conn;

// Fetch user details
$username = $_SESSION['username'];
$user_query = $mysqli->prepare("SELECT id, email, first_name, last_name, profile_picture FROM crud WHERE username = ?");
$user_query->bind_param('s', $username);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();

// Fetch clicked event details
$event_id = $_GET['id']; // Assuming you're passing the event ID through URL
$event_query = $mysqli->prepare("SELECT id, name, image, description, created_at, approved FROM events WHERE id = ?");
$event_query->bind_param('i', $event_id);
$event_query->execute();
$event_result = $event_query->get_result();
$event = $event_result->fetch_assoc();

// Fetch related events
$related_events_query = $mysqli->prepare("SELECT id, name, image, description, created_at, approved FROM events WHERE user_id = ? AND id != ?");
$related_events_query->bind_param('ii', $user['id'], $event_id);
$related_events_query->execute();
$related_events_result = $related_events_query->get_result();

// Initialize $related_events to an empty array
$related_events = [];
if ($related_events_result !== false) {
    while ($related_event = $related_events_result->fetch_assoc()) {
        $related_events[] = $related_event;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['name']); ?> - Event Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Your custom styles */
        .event-details {
            margin-top: 20px;
        }
        .related-events {
            margin-top: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .event-card {
            border: 2px solid #007bff; /* Dark blue border */
        }
        .event-card-header {
            background-color: #007bff; /* Dark blue background */
            color: #fff; /* White text */
        }
        .event-card-footer {
            background-color: #f8f9fa; /* Light grey background */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <!-- Main event details -->
            <div class="col-md-8">
                <div class="event-details">
                    <div class="card event-card">
                        <div class="card-header event-card-header">
                            <h1 class="card-title"><?php echo htmlspecialchars($event['name']); ?></h1>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($event['image'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($event['image']); ?>" alt="Event Image" class="img-fluid mb-3">
                            <?php endif; ?>
                            <p><strong>Posted on:</strong> <?php echo htmlspecialchars($event['created_at']); ?></p>
                            <p><?php echo htmlspecialchars($event['description']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Related events on the right -->
            <div class="col-md-4">
                <div class="related-events">
                    <h2>Related Events</h2>
                    <div class="row">
                        <?php foreach ($related_events as $related_event): ?>
                            <div class="col-md-12">
                                <div class="card">
                                    <img src="<?php echo !empty($related_event['image']) ? "uploads/" . htmlspecialchars($related_event['image']) : "path_to_placeholder_image.jpg"; ?>" alt="Related Event Image" class="card-img-top">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($related_event['name']); ?></h5>
                                        <!-- Add more details if needed -->
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- JS scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body
