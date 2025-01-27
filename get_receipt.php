<?php
// Include database connection
include 'db_connection.php';

if (isset($_GET['adoption_id'])) {
    $adoption_id = $_GET['adoption_id'];

    // Fetch adoption details
    $query = "SELECT adoptions.*, pets.name AS pet_name FROM adoptions LEFT JOIN pets ON adoptions.pet_id = pets.pet_id WHERE adoptions.adoption_id = '$adoption_id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        echo '<div style="text-align: center;">';
        echo '<img src="Pet Adoption.png" alt="Website Logo" style="width: 150px;"/>';
        echo '<h2>Adoption Receipt</h2>';
        echo '</div>';

        echo '<table style="width: 100%; border-collapse: collapse;">';
        echo '<tr><th style="border: 1px solid #ddd; padding: 8px;">Pet Name</th><td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($row['pet_name']) . '</td></tr>';
        echo '<tr><th style="border: 1px solid #ddd; padding: 8px;">Full Name</th><td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($row['full_name']) . '</td></tr>';
        echo '<tr><th style="border: 1px solid #ddd; padding: 8px;">Email</th><td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($row['email']) . '</td></tr>';
        echo '<tr><th style="border: 1px solid #ddd; padding: 8px;">Phone</th><td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($row['phone']) . '</td></tr>';
        echo '<tr><th style="border: 1px solid #ddd; padding: 8px;">Application Status</th><td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($row['application_status']) . '</td></tr>';
        echo '<tr><th style="border: 1px solid #ddd; padding: 8px;">Payment Status</th><td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($row['payment_status']) . '</td></tr>';
        echo '</table>';
    } else {
        echo 'No receipt details found.';
    }
}
?>
