<?php
require('config.php');
include 'HomeHeader.php';
?>

<style>
    .card {
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
        font-family: 'New Roman', serif;
    }

    .card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .card-title {
        font-weight: bold;
        font-size: 18px;
        background-color: #1C1D3C !important; /* Updated background color for title */
        color: #fff; /* Changed text color for better visibility */
        padding: 8px 12px; /* Added padding for better appearance */
        border-top-left-radius: 5px; /* Rounded top left corner */
        border-top-right-radius: 5px; /* Rounded top right corner */
        margin-bottom: 0; /* Removed margin to align with card body */
        cursor: pointer; /* Add cursor pointer for clickable effect */
    }

    .card-text {
        font-family: 'Times New Roman', Times, serif;
        font-size: 16px;
        font-weight: 500;
    }

    .card-body {
        padding: 20px;
        position: relative;
    }

    .related-events .card-img-top {
        width: 100%;
        height: auto; /* Changed height to auto for better resizing */
        max-height: 200px; /* Added max-height for image */
        object-fit: cover;
    }

    .read-more-btn {
        bottom: 10px;
        transform: translateX(100%);
        background-color: #1C1D3C !important; /* Updated background color for button */
        color: #fff; /* Changed text color for better visibility */
    }
</style>

<?php
if(isset($_GET['id'])) {
    $event_id = $_GET['id'];
    $sql = "SELECT * FROM events WHERE id = $event_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
        ?>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <?php
                        if(!empty($event['image'])) {
                            $image_url = "uploads/" . $event['image'];
                        } else {
                            $image_url = "path_to_placeholder_image.jpg";
                        }
                        ?>
                        <img src="<?php echo $image_url; ?>" class="card-img-top" alt="<?php echo $event['name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title" onclick="toggleCard(this)"><?php echo $event['name']; ?></h5> <!-- Added onclick event -->
                            <p class="card-text"><?php echo nl2br($event['description']); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 related-events">
                    <h2 style="background-color: #1C1D3C; color: #fff; padding: 10px;">Related Events</h2>
                    <div id="related-events-container">
                        <?php
                        $sql_related = "SELECT * FROM events WHERE id != $event_id LIMIT 4";
                        $result_related = $conn->query($sql_related);

                        if ($result_related->num_rows > 0) {
                            while($row_related = $result_related->fetch_assoc()) {
                                if(!empty($row_related['image'])) {
                                    $related_image_url = "uploads/" . $row_related['image'];
                                } else {
                                    $related_image_url = "path_to_placeholder_image.jpg"; 
                                }
                                ?>
                                <div class="card mb-3">
                                    <img src="<?php echo $related_image_url; ?>" class="card-img-top" alt="<?php echo $row_related['name']; ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $row_related['name']; ?></h5>
                                        <p class="card-text"><?php echo substr($row_related['description'], 0, 100); ?></p>
                                        <a href="event_details.php?id=<?php echo $row_related['id']; ?>" class="btn btn-primary read-more-btn">Read More</a>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo "No related events found";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo "Event not found";
    }
} else {
    echo "Invalid request";
}

$conn->close();
include 'footer.php';
?>

<script>
    function toggleCard(element) {
        // Add toggle functionality here
    }

    function displayEventContent(eventId) {
        // Redirect or display content for the clicked event
        window.location.href = 'event_details.php?id=' + eventId;
    }
</script>
