<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

// Fetch the username, email, and phone from the session or database
$name = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";
$email = isset($_SESSION['email']) ? $_SESSION['email'] : $_SESSION['email'];

// Fetch phone number from the database
$sql = "SELECT phone FROM users WHERE email='$email'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$phone = $row['phone'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];
    $new_phone = $_POST['phone'];

    // Update user information in the database
    $sql = "UPDATE users SET name='$new_name', email='$new_email', phone='$new_phone' WHERE email='$email'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['name'] = $new_name;
        $_SESSION['email'] = $new_email;
        echo "<script>alert('Profile updated successfully.');</script>";
    } else {
        echo "<script>alert('Error updating profile: " . mysqli_error($conn) . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .profile-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .profile-container h2 {
            text-align: center;
            color: #333;
        }

        .profile-container form {
            display: flex;
            flex-direction: column;
        }

        .profile-container input {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .profile-container button {
            background-color: #000;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .profile-container button:hover {
            background-color: #333;
        }

        .back-button {
            background-color: #555;
            margin-top: 10px;
        }

        .back-button:hover {
            background-color: #777;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>Edit Profile</h2>
        <form method="POST">
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="Name" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Email" required>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="Phone Number" required>
            <button type="submit">Update Profile</button>
        </form>
        <button class="back-button" onclick="history.back();">Back</button>
    </div>
</body>
</html>
