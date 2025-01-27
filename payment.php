<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

// Check if the adoption ID is provided
if (isset($_GET['adoption_id'])) {
    $adoption_id = $_GET['adoption_id'];

    // Fetch the adoption details from the database
    $sql = "SELECT adoptions.*, pets.email AS uploader_email FROM adoptions JOIN pets ON adoptions.pet_id = pets.pet_id WHERE adoptions.adoption_id = '$adoption_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $adoption = mysqli_fetch_assoc($result);

        // Check if the payment status is already "PAID"
        if ($adoption['payment_status'] == 'PAID') {
            echo "Payment has already been completed for this adoption.";
            exit;
        }

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Simulate payment processing
            $account_number = $_POST['account_number'];
            $password = $_POST['password'];

            // Here you would normally validate and process the payment
            // For this example, we'll assume the payment is successful

            // Update the adoption status to "approved" and payment status to "PAID"
            $update_sql = "UPDATE adoptions SET application_status = 'Approved', payment_status = 'PAID' WHERE adoption_id = '$adoption_id'";
            // Also update the pet's availability to "unavailable"
            $pet_id = $adoption['pet_id'];
            $update_pet_sql = "UPDATE pets SET availability = 'unavailable' WHERE pet_id = '$pet_id'";

            // Insert payment record into payments table
            $uploader_email = $adoption['uploader_email']; // Use the uploader's email
            $amount = 200; // Example amount, replace with actual logic
            $insert_payment_sql = "INSERT INTO payments (email, amount, payment_date, status, pet_id) VALUES ('$uploader_email', '$amount', NOW(), 'PAID', '$pet_id')";

            if (mysqli_query($conn, $update_sql) && mysqli_query($conn, $update_pet_sql) && mysqli_query($conn, $insert_payment_sql)) {
                echo "<script>alert('Payment successful. Your application has been approved.'); window.location.href='status.php';</script>";
                exit;
            } else {
                echo "Error updating payment status: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Adoption not found.";
    }
} else {
    echo "No adoption ID specified.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .payment-form {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .payment-form h2 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }
        .payment-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .payment-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .payment-form button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .payment-form button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="payment-form">
        <h2>Complete Your Payment</h2>
        <form method="POST">
            <label for="account_number">Account Number</label>
            <input type="text" id="account_number" name="account_number" placeholder="Account Number" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit">Submit Payment</button>
        </form>
    </div>
</body>
</html>
