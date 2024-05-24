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

// Fetch user events
$event_query = $mysqli->prepare("SELECT id, name, image, description, created_at, approved FROM events WHERE user_id = ?");
$event_query->bind_param('i', $user['id']);
$event_query->execute();
$event_result = $event_query->get_result();

// Initialize $events array
$events = [];
if ($event_result !== false) {
    while ($event = $event_result->fetch_assoc()) {
        $events[] = $event;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Zetech University</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-header {
            text-align: center;
            margin: 20px 0;
        }
        .profile-picture {
            border-radius: 50%;
            width: 150px;
            height: 150px;
        }
        .profile-details {
            margin-top: 20px;
        }
        .card img {
            width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: cover;
        }
        .card-body {
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .read-more {
            cursor: pointer;
            color: #333;
        }
        .read-more-content {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-header">
            <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="profile-picture">
            <h1><?php echo htmlspecialchars($username); ?></h1>
        </div>
        <div class="profile-details">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>First Name:</strong> <?php echo htmlspecialchars($user['first_name']); ?></p>
            <p><strong>Last Name:</strong> <?php echo htmlspecialchars($user['last_name']); ?></p>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($events as $event): ?>
                <?php
                $status = $event['approved'] == 1 ? '' : '(pending)';
                $image_url = !empty($event['image']) ? "uploads/" . htmlspecialchars($event['image']) : "path_to_placeholder_image.jpg";
                ?>
                <div class="col">
                    <div class="card">
                        <img src="<?php echo $image_url; ?>" alt="Event Image" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($event['name']) . $status; ?></h5>
                            <?php
                            $description = htmlspecialchars($event['description']);
                            $words = explode(' ', $description);
                            $shortDescription = implode(' ', array_slice($words, 0, 20));
                            $longDescription = implode(' ', array_slice($words, 20));
                            ?>
                            <p class="card-text">
                                <span class="short-description"><?php echo $shortDescription; ?></span>
                                <a href="user-event_details.php?id=<?php echo $event['id']; ?>" class="read-more">Read More</a>
                            </p>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Posted on <?php echo htmlspecialchars($event['created_at']); ?></small>
                        </div>
                        <?php if ($event['approved'] == 0): ?>
                            <div class="card-footer">
                                <a href="user-edit.php" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete-event.php?id=<?php echo $event['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="mt-4">
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="profile_picture" class="form-label">Upload New Profile Picture:</label>
                    <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                </div> <button type="submit" class="btn btn-primary">Upload</button>
                <?php if (!empty($user['profile_picture'])): ?>
                    <button type="submit" class="btn btn-danger" name="remove_picture">Remove Picture</button>
                <?php endif; ?>
               
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
<?php
include 'footer.php';
?>