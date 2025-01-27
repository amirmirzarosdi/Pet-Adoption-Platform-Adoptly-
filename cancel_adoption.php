<?php
session_start();
include 'db_connection.php';

if (isset($_GET['adoption_id'])) {
    $adoption_id = $_GET['adoption_id'];
    $sql = "DELETE FROM adoptions WHERE adoption_id = '$adoption_id' AND email = '{$_SESSION['email']}'";
    if (mysqli_query($conn, $sql)) {
        header("Location: status.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
