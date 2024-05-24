<?php
require('config.php');
include 'user-nav.php';

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    $sql = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
?>
        <div class="container mt-4 mb-4">
            <div class="card">
                <div class="card-body p-4">
                    <style>
                        .label-bold {
                            font-weight: bold;
                            color: #333; 
                        }

                        .input-normal {
                            font-weight: normal;
                            color: #777; 
                        }
                        .file-input input[type=file] {
                            display: none;
                        }
                    </style>
                    <h2 class="card-title mb-4">Edit Event</h2>
                    <form action="update_event.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <div class="form-group mb-4">
                            <label for="name" class="label-bold">Event Title:</label>
                            <input type="text" class="form-control input-normal" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>">
                        </div>
                        <div class="form-group mb-4">
                            <label for="image" class="label-bold">Image:</label>
                            <div class="file-input">
                                <input type="file" class="form-control-file input-normal" id="image" name="image" onchange="updateFileName(this)">
                                <label for="image" class="input-group-text"><?php echo htmlspecialchars($row['image']); ?></label>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="description" class="label-bold">Description:</label>
                            <textarea class="form-control auto-height input-normal" id="description" name="description"><?php echo htmlspecialchars($row['description']); ?></textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label for="location" class="label-bold">Event Location:</label>
                            <input type="text" class="form-control input-normal" id="location" name="location" value="<?php echo htmlspecialchars($row['location']); ?>">
                        </div>
                        <div class="form-group mb-4">
                            <label for="date" class="label-bold">Event Date:</label>
                            <input type="date" class="form-control input-normal" id="date" name="date" value="<?php echo htmlspecialchars($row['date']); ?>">
                        </div>
                        <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($row['image']); ?>">
                        <button type="submit" class="btn btn-primary mt-4">Update Event</button>
                    </form>
                </div>
            </div>
        </div>
        <script>
            function updateFileName(input) {
                var label = input.nextElementSibling;
                if (input.files.length > 0) {
                    label.textContent = input.files[0].name;
                } else {
                    label.textContent = "<?php echo htmlspecialchars($row['image']); ?>";
                }
            }
        </script>
<?php
    } else {
        echo "Event not found";
    }
} else {
    echo "Event ID not provided. Please check the URL and ensure it includes the event ID.";
    echo "<p>Current URL: " . htmlspecialchars($_SERVER['REQUEST_URI']) . "</p>";
}

$conn->close();
?>

<!-- Footer -->
<div class="fixed-bottom mt-5">
<?php include 'footer.php'; ?>
</div>
