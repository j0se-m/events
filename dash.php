<?php
// session_start();

// Redirect to login page if user is not logged in or not an admin
// if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
//     // header("location: dash.php");
//     exit();
// }

require('config.php');
require('headerr.php');


// Initialize variables to store search criteria
$searchName = '';
$searchLocation = '';
$searchStatus = '';

// Check if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get search criteria from the form
    $searchName = $_POST['name'] ?? '';
    $searchLocation = $_POST['location'] ?? '';
    $searchStatus = $_POST['status'] ?? '';

    // Construct the SQL query based on search criteria
    $sql = "SELECT * FROM events WHERE name LIKE '%$searchName%' AND location LIKE '%$searchLocation%'";
    if (!empty($searchStatus)) {
        if ($searchStatus == 'approved') {
            $sql .= " AND approved = 1"; // Approved status
        } elseif ($searchStatus == 'pending') {
            $sql .= " AND approved = 0"; // Pending status
        }
    }

    $result = $conn->query($sql);

    // Fetch events based on search criteria
    $events = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Your custom styles here */
    </style>
</head>
<body>
    <!-- Navbar code here -->

    <div class="container mt-4">
        <h2>Search Events</h2>
        <form method="POST">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <input type="text" class="form-control" id="search_name" name="name" placeholder="Event Name" value="<?php echo $searchName; ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <input type="text" class="form-control" id="search_location" name="location" placeholder="Location" value="<?php echo $searchLocation; ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <select class="form-select" id="search_status" name="status">
                        <option value="">Select Status</option>
                        <option value="approved" <?php if ($searchStatus == 'approved') echo 'selected'; ?>>Approved</option>
                        <option value="pending" <?php if ($searchStatus == 'pending') echo 'selected'; ?>>Pending</option>
                    </select>
                </div>
                <div class="col-md-1 mb-3">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>

        <div class="table-responsive mt-4">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($events) && !empty($events)): ?>
                        <?php foreach ($events as $event): ?>
                            <tr>
                                <td><?php echo $event['name']; ?></td>
                                <td><?php echo $event['location']; ?></td>
                                <td><?php echo $event['date']; ?></td>
                                <td><?php echo ($event['approved'] == 1) ? 'Approved' : 'Pending'; ?></td> <!-- Assuming 'approved' is the column name for status -->
                                <td>
                                    <a href="#" class="btn btn-primary">Edit</a>
                                    <a href="#" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No events found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
