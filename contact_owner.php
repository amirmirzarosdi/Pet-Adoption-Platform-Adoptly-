<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

// Check if pet_id and adoption_id are set
if (isset($_GET['pet_id']) && isset($_GET['adoption_id'])) {
    $pet_id = $_GET['pet_id'];
    $adoption_id = $_GET['adoption_id'];

    // Fetch owner details from the database using pet_id
    $owner_query = "SELECT users.email, users.phone FROM pets 
                    JOIN users ON pets.email = users.email 
                    WHERE pets.pet_id = '$pet_id'";
    $owner_result = mysqli_query($conn, $owner_query);

    if ($owner_result && mysqli_num_rows($owner_result) > 0) {
        $owner_details = mysqli_fetch_assoc($owner_result);
        $owner_email = $owner_details['email'];

        // Auto-generated message
        $subject = "Pet Adoption Update: Adoption ID $adoption_id";
        $message = "Dear Owner,\n\nThe pet associated with adoption ID $adoption_id has been paid and approved. Please provide the location details for the pet.\n\nThank you.";

        // Mailto URL
        $mailto_url = "mailto:$owner_email?subject=" . urlencode($subject) . "&body=" . urlencode($message);

        // Display the message and Email button
        echo "<div class='contact-container'>";
        echo "<h2>Contact Owner</h2>";
        echo "<p>To: $owner_email</p>";
        echo "<p>Message:</p>";
        echo "<textarea rows='10' cols='50' readonly>" . htmlspecialchars($message) . "</textarea>";
        echo "<a href='$mailto_url' class='email-btn'>Send via Email</a>";
        echo "<button class='back-btn' onclick='history.back();'>Back</button>";
        echo "</div>";
    } else {
        echo "<p>Owner details not found.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 20px;
    }

    .contact-container {
        max-width: 600px;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #333;
        text-align: center;
    }

    p {
        color: #555;
    }

    textarea {
        width: 100%;
        padding: 10px;
        border-radius: 4px;
        border: 1px solid #ddd;
        margin-bottom: 20px;
        resize: none;
    }

    .email-btn {
        display: block;
        width: 98%;
        text-align: center;
        background-color: #007bff;
        color: white;
        padding: 10px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s ease;
        margin-bottom: 10px;
    }

    .email-btn:hover {
        background-color: #0056b3;
    }

    .back-btn {
        display: block;
        width: 100%;
        text-align: center;
        background-color: #555;
        color: white;
        padding: 10px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .back-btn:hover {
        background-color: #777;
    }
</style>
