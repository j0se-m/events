<?php
require 'config.php';
include 'headerr.php';

// Check if user is admin
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: approved.php");
    exit();
}

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$sql = "SELECT events.*, crud.first_name, crud.last_name 
        FROM events 
        JOIN crud ON events.user_id = crud.id 
        WHERE events.approved = 1 
        ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="container mt-3">
    <div class="row">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>"
                             class="card-img-top" style="object-fit: cover; height: 150px;"
                             alt="<?php echo htmlspecialchars($row['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr($row['description'], 0, 100)); ?>...</p>
                            <p class="card-text"><strong>Posted by:</strong> <?php echo htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?></p>
                            <div class="admin-buttons">
                                <a href="event_details.php?id=<?php echo htmlspecialchars($row['id']); ?>"
                                   class="btn btn-sm btn-info">Read More</a>
                                <a href="edit_event.php?id=<?php echo htmlspecialchars($row['id']); ?>"
                                   class="btn btn-sm btn-warning">Edit</a>
                                <button onclick="confirmDelete(<?php echo htmlspecialchars($row['id']); ?>)"
                                        class="btn btn-sm btn-danger">Delete</button>
                                <form action="disapprove_event.php" method="post" class="d-inline">
                                    <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <button type="submit" class="btn btn-sm btn-secondary">Disapprove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No events found</p>";
        }

        mysqli_close($conn);
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    // JavaScript function to confirm event deletion
    function confirmDelete(eventId) {
        if (confirm("Are you sure you want to delete this event?")) {
            window.location.href = 'delete_event.php?id=' + eventId;
        }
    }
</script>
