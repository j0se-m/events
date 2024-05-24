<?php
require_once 'config.php';
require_once 'headerr.php'; // Include your header file

// Fetch user logs from the database
$sql = "SELECT * FROM user_logs";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">User Login/Logout Times</h2>
        <?php
        // Check if there are any logs
        if ($result->num_rows > 0) {
            echo '<table class="table table-bordered table-striped">';
            echo '<thead class="table-dark">';
            echo '<tr><th>ID</th><th>Username</th><th>Login Time</th><th>Logout Time</th></tr>';
            echo '</thead><tbody>';

            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['username'] . '</td>';
                echo '<td>' . $row['login_time'] . '</td>';
                echo '<td>' . ($row['logout_time'] ?? 'Not logged out yet') . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
        } else {
            echo '<p class="text-center">No user logs found.</p>';
        }

        $conn->close(); // Close the database connection
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
<?php include 'footer.php';?>