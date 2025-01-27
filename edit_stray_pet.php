<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

// Fetch the username and email from the session
$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;

// Check if pet_id is provided
if (!isset($_GET['pet_id'])) {
    die("Pet ID not specified.");
}

$pet_id = $_GET['pet_id'];

// Fetch the pet details
$query = "SELECT * FROM stray_pets WHERE id = ? AND user_email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('is', $pet_id, $email);
$stmt->execute();
$result = $stmt->get_result();
$pet = $result->fetch_assoc();

if (!$pet) {
    die("Pet not found or you do not have permission to edit this pet.");
}

// Handle form submission for editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Handle image upload
    $image = $_FILES['image']['name'];
    if ($image) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Update pet details including the new image
            $update_query = "UPDATE stray_pets SET description = ?, location = ?, image = ? WHERE id = ? AND user_email = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param('sssis', $description, $location, $target_file, $pet_id, $email);
            $update_stmt->execute();
        } else {
            echo "<script>alert('Failed to upload image.');</script>";
        }
    } else {
        // Update pet details without changing the image
        $update_query = "UPDATE stray_pets SET description = ?, location = ?, status = ? WHERE id = ? AND user_email = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param('sssis', $description, $location, $status, $pet_id, $email);
        $update_stmt->execute();
    }

    // Check if the update was successful
    if ($update_stmt->affected_rows > 0) {
        echo "<script>alert('Stray pet updated successfully!'); window.location.href='uploadstray.php';</script>";
    } else {
        echo "<script>alert('Failed to update stray pet.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Stray Pet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .edit-form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        .edit-form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .edit-form-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .edit-form-container label {
            font-weight: bold;
        }

        .edit-form-container textarea,
        .edit-form-container input[type="url"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }

        .edit-form-container button {
            padding: 10px;
            background-color: #000000;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .edit-form-container button:hover {
            background-color: #333;
        }

        .edit-form-container .back-button {
            background-color: #555;
        }

        .edit-form-container .back-button:hover {
            background-color: #777;
        }
    </style>
</head>
<body>
    <div class="edit-form-container">
        <h2>Edit Stray Pet</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="description">Description:</label>
            <textarea name="description" required><?php echo htmlspecialchars($pet['description']); ?></textarea><br>
            <label for="location">Location (Link):</label>
            <input type="url" name="location" value="<?php echo htmlspecialchars($pet['location']); ?>" required><br>
            <label for="status">Status:</label>
            <select name="status" required>
                <option value="available" <?php echo $pet['status'] === 'available' ? 'selected' : ''; ?>>Available</option>
                <option value="adopted" <?php echo $pet['status'] === 'adopted' ? 'selected' : ''; ?>>Adopted</option>
            </select><br>
            <button type="submit">Update</button>
        </form>

        <br>
        <button class="back-button" onclick="window.location.href='uploadstray.php';">Back</button>
    </div>
</body>
</html>
