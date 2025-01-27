<?php
include 'db_connection.php';
session_start();

if (isset($_GET['pet_id'])) {
    $pet_id = $_GET['pet_id'];
    $email = $_SESSION['email']; // Ensure only the user who uploaded the pet can delete it

    // Delete the pet from the database
    $sql = "DELETE FROM pets WHERE pet_id = '$pet_id' AND email = '$email'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Pet deleted successfully'); window.location.href='uploadpet.php';</script>";
    } else {
        echo "<script>alert('Failed to delete pet'); window.location.href='uploadpet.php';</script>";
    }
} else {
    echo "<script>alert('Pet ID not provided'); window.location.href='uploadpet.php';</script>";
}
?>
