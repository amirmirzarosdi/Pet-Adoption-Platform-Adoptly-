<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));

        // Store the token in the database with an expiration time
        $sql = "UPDATE users SET reset_token = ?, token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $token, $email);
        $stmt->execute();

        // Send reset link to user's email
        $resetLink = "http://localhost/pet_adoption_platform/reset_password.php?token=$token";
        mail($email, "Password Reset", "Click the following link to reset your password: $resetLink");

        echo "<script>alert('Password reset link has been sent to your email.');</script>";
    } else {
        echo "<script>alert('Email not found.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="loginsignup.css">
    <style>
        /* Additional styles for the forgot password page */
        .forgot-password-container {
            width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        .forgot-password-container h1 {
            margin-bottom: 20px;
            color: var(--grad-clr1);
        }

        .forgot-password-container button {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <form action="" method="POST">
            <h1>Forgot Password</h1>
            <div class="infield">
                <input type="email" placeholder="Enter your email" name="email" required />
                <label></label>
            </div>
            <button type="submit" name="reset_password">Send Reset Link</button>
        </form>
    </div>
</body>
</html>

