<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

// Fetch the username and email from the session
$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;

// Check if pet_id is provided
if (!isset($_GET['pet_id'])) {
    die("Pet ID not specified.");
}

$pet_id = $_GET['pet_id'];

// Delete the pet record
$query = "DELETE FROM stray_pets WHERE id = ? AND user_email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('is', $pet_id, $email);

if ($stmt->execute()) {
    echo "<script>alert('Stray pet deleted successfully!'); window.location.href='uploadstray.php';</script>";
} else {
    echo "<script>alert('Failed to delete stray pet.'); window.location.href='uploadstray.php';</script>";
}

$conn->close();
?>
