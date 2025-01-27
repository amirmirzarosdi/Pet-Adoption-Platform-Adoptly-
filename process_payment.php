<?php
// Include database connection
include 'db_connection.php';

if (isset($_GET['adoption_id'])) {
    $adoption_id = $_GET['adoption_id'];

    // Update payment status to PAID
    $update_payment_query = "UPDATE adoptions SET payment_status = 'PAID' WHERE adoption_id = '$adoption_id'";
    if (mysqli_query($conn, $update_payment_query)) {
        // Redirect back to status.php
        header("Location: status.php?adoption_id=$adoption_id");
        exit();
    } else {
        echo "Error updating payment status.";
    }
} else {
    echo "Adoption ID not provided.";
}
?>
