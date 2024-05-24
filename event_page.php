<?php
include 'config.php';
require('headerr.php');

$event_id = $_GET['id'] ?? '';

$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $event = $result->fetch_assoc();
} else {
    header("Location: 404.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $event['name']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
    <style>
        /* Custom styles */
        .event-container {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .event-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .form-control-sm, .form-select-sm, .btn-sm {
            width: 100%;
            display: inline-block;
            vertical-align: middle;
        }

        .description {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Show only 3 lines */
            -webkit-box-orient: vertical;
        }

        .read-more {
            color: #007bff;
            cursor: pointer;
            display: block;
            margin-top: 10px;
        }

        .read-more:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Navbar code here -->

    <div class="container mt-4">
        <div class="event-container">
            <h2><?php echo $event['name']; ?></h2>
            <p><strong>Location:</strong> <?php echo $event['location']; ?></p>
            <p><strong>Date:</strong> <?php echo $event['date']; ?></p>
            <p><strong>Status:</strong> <?php echo $event['approved'] ? 'Approved' : 'Pending'; ?></p>
            <img src="uploads/<?php echo $event['image']; ?>" alt="Event Image" class="event-image">

            <div class="mt-4">
                <h3>Description</h3>
                <div id="description" class="description"><?php echo $event['description']; ?></div>
                <span class="read-more">Read more</span>
            </div>

            <!-- Additional details or sections as needed -->
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const readMore = document.querySelector('.read-more');
            const description = document.querySelector('.description');

            readMore.addEventListener('click', function() {
                description.style.display = '-webkit-box';
                description.style.webkitLineClamp = 'unset';
                readMore.style.display = 'none';
            });

            ClassicEditor
                .create(document.querySelector('#description'))
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
</body>
</html>
