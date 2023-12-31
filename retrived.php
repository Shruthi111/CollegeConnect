<?php
// Start the session
session_start();

// Check if the user is logged in
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    include 'db_connect.php';

    // Retrieve the profile picture of the logged-in user
    $sql = "SELECT `Profilepic` FROM `Users` WHERE ID = '$userId'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $profilePic = $row['Profilepic'];

        // Set the appropriate header for the image
        header("Content-type: image/jpeg");

        // Output the profile picture
        echo $profilePic;
    } else {
        echo "Failed to retrieve the profile picture.";
    }

    mysqli_close($conn);
} else {
    echo "User not logged in.";
}
?>
