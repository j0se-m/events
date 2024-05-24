<?php
require('config.php');
include 'headerr.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<style>
    .container {
        margin-bottom: 30px;
    }

    .card {
        height: 500px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
        margin-bottom: 30px;
    }

    .card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .btn-primary {
        background-color: #1C1D3C;
    }

    .btn-edit,
    .btn-delete {
        margin-top: 12px;
        margin-right: 5px;
    }

    .btn-container {
        display: flex;
        justify-content: space-between;
    }

    .pending-text {
        color: red;
        font-weight: bold;
    }
</style>

<div class="container">
    <?php
    $sql = "SELECT * FROM events WHERE approved = 1 ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<div class="row">';
        while ($row = $result->fetch_assoc()) {
            echo '<div class="col-lg-4 col-md-6 mt-5">';
            echo '<div class="card h-100">';

            if (!empty($row['image'])) {
                $image_url = "uploads/" . $row['image'];
            } else {
                $image_url = "path_to_placeholder_image.jpg";
            }

            echo '<img src="' . $image_url . '" class="card-img-top" style="object-fit: cover; height: 150px;" alt="' . $row['name'] . '">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $row['name'] . '</h5>';
            echo '<p class="card-text">' . substr($row['description'], 0, 100) . '...</p>';

            echo '<div class="btn-container">';
            echo '<a href="user-readmore.php?id=' . $row['id'] . '" class="btn btn-primary">Read More</a>';

            // Check if the event is created by the current user and not approved
            if ($row['user_id'] == $user_id && $row['approved'] == 0) {
                echo '<a href="edit-event.php?id=' . $row['id'] . '" class="btn btn-warning btn-edit">Edit</a>';
                echo '<a href="delete-event.php?id=' . $row['id'] . '" class="btn btn-danger btn-delete" onclick="return confirm(\'Are you sure you want to delete this post?\')">Delete</a>';
            }

            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo "No events found";
    }
    $conn->close();
    ?>
</div>

<?php
include 'footer.php';
?>
