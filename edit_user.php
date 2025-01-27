<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

// Check if user ID is provided
if (!isset($_GET['id'])) {
    die("User ID not specified.");
}

$user_id = $_GET['id'];

// Fetch the user details
$query = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Handle form submission for editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];
    $new_phone = $_POST['phone'];

    // Update user information in the database
    $update_query = "UPDATE users SET name = ?, email = ?, phone = ? WHERE email = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('ssss', $new_name, $new_email, $new_phone, $user_id);
    $update_stmt->execute();

    if ($update_stmt->affected_rows > 0) {
        echo "<script>alert('User updated successfully!'); window.location.href='user_list.php';</script>";
    } else {
        echo "<script>alert('Failed to update user.');</script>";
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

        .edit-user-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .edit-user-container h2 {
            text-align: center;
            color: #333;
        }

        .edit-user-container form {
            display: flex;
            flex-direction: column;
        }

        .edit-user-container input {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .edit-user-container button {
            background-color: #000;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .edit-user-container button:hover {
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
    <div class="edit-user-container">
        <h2>Edit User</h2>
        <form method="POST">
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" placeholder="Name" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Email" required>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" placeholder="Phone Number" required>
            <button type="submit">Update User</button>
        </form>
        <button class="back-button" onclick="window.location.href='user_list.php';">Back</button>
    </div>
</body>
</html>
