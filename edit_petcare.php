<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

// Fetch the record for editing
if (isset($_GET['id'])) {
    $edit_id = $_GET['id'];
    $query = "SELECT * FROM pet_care_resources WHERE petcare_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $resource = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission for updating the resource
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $edit_id = $_POST['edit_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $link = $_POST['link'];
    $type = $_POST['type'];

    // Handle image upload if a new image is provided
    $target_file = $resource['image_path'];
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size (limit to 5MB)
        if ($_FILES["image"]["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Update the resource in the database
    $stmt = $conn->prepare("UPDATE pet_care_resources SET title = ?, description = ?, link = ?, image_path = ?, type = ? WHERE petcare_id = ?");
    $stmt->bind_param("sssssi", $title, $description, $link, $target_file, $type, $edit_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to the admin page
    header("Location: adminpetcare.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f9f9f9;
        }

        .form-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-container input,
        .form-container textarea,
        .form-container select,
        .form-container button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-container button {
            background-color: #000000;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #333;
        }
    </style>
<body>
<div class="form-container">
    <h2>Edit Pet Care Resource</h2>
    <form method="POST" action="edit_petcare.php?id=<?php echo $resource['petcare_id']; ?>" enctype="multipart/form-data">
        <input type="hidden" name="edit_id" value="<?php echo $resource['petcare_id']; ?>">
        <input type="text" name="title" value="<?php echo htmlspecialchars($resource['title']); ?>" required>
        <textarea name="description" required><?php echo htmlspecialchars($resource['description']); ?></textarea>
        <input type="url" name="link" value="<?php echo htmlspecialchars($resource['link']); ?>" required>
        <input type="file" name="image" accept="image/*">
        <select name="type" required>
            <option value="Clinical" <?php if ($resource['type'] == "Clinical") echo "selected"; ?>>Clinical</option>
            <option value="Accessories" <?php if ($resource['type'] == "Accessories") echo "selected"; ?>>Accessories</option>
            <option value="Others" <?php if ($resource['type'] == "Others") echo "selected"; ?>>Others</option>
        </select>
        <button type="submit">Update Resource</button>
        <button class="back-button" onclick="window.location.href='adminpetcare.php';">Back</button>
    </form>
</div>
</body>
</html>
