<?php
session_start();

// Check if the username is set in the session
if (isset($_SESSION['username'])) {
    // Store the username in a variable
    $username = $_SESSION['username'];

    // Include the database configuration file
    require('config.php');

    // Establish a database connection
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        die("Connection error: " . mysqli_connect_error());
    }

    // Update the logout time in the user_logs table
    $sql = "UPDATE user_logs SET logout_time = NOW() WHERE username = '$username' AND logout_time IS NULL";
    $conn->query($sql);

    // Close the database connection
    mysqli_close($conn);
}

// Destroy the session
session_destroy();

// Redirect to the home page
header("location:Home.php");
exit();
?>
