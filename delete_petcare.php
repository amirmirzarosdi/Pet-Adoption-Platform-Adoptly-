<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

// Check if the id is set in the URL
if (isset($_GET['id'])) {
    $delete_id = $_GET['id'];

    // Delete the resource from the database
    $stmt = $conn->prepare("DELETE FROM pet_care_resources WHERE petcare_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to the admin page
    header("Location: adminpetcare.php");
    exit();
} else {
    echo "Invalid request.";
}
?>
