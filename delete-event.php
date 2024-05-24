<?php
require('config.php');
session_start(); // Ensure the session is started

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Prepare the SQL statement to delete the event
    $sql = "DELETE FROM events WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ii", $event_id, $user_id);
        
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: user-home.php?message=Event deleted successfully");
            exit();
        } else {
            $stmt->close();
            $conn->close();
            header("Location: user-home.php?error=Error deleting event");
            exit();
        }
    } else {
        $conn->close();
        header("Location: user-home.php?error=Failed to prepare statement");
        exit();
    }
} else {
    header("Location: user-home.php?error=Event ID not provided");
    exit();
}
?>
