<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
    $token = $_POST['token'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Verify the token and check expiration
    $sql = "SELECT * FROM users WHERE reset_token = ? AND token_expiry > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update the password
        $sql = "UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE reset_token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $newPassword, $token);
        $stmt->execute();

        echo "<script>alert('Password has been updated successfully.'); window.location.href = 'loginsignup.php';</script>";
    } else {
        echo "<script>alert('Invalid or expired token.');</script>";
    }
} else if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    echo "<script>alert('No token provided.'); window.location.href = 'loginsignup.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="loginsignup.css">
    <style>
        /* Additional styles for the reset password page */
        .reset-password-container {
            width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        .reset-password-container h1 {
            margin-bottom: 20px;
            color: var(--grad-clr1);
        }

        .reset-password-container button {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="reset-password-container">
        <form action="" method="POST">
            <h1>Reset Password</h1>
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" />
            <div class="infield">
                <input type="password" placeholder="Enter new password" name="new_password" required />
                <label></label>
            </div>
            <button type="submit" name="update_password">Update Password</button>
        </form>
    </div>
</body>
</html>
