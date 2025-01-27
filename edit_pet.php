<?php
// Include database connection file
include('db_connection.php');

// Check if the pet ID is provided in the URL
if (isset($_GET['pet_id'])) {
    $pet_id = $_GET['pet_id'];

    // Fetch the pet details from the database
    $query = "SELECT * FROM pets WHERE pet_id = $pet_id";
    $result = mysqli_query($conn, $query);
    $pet = mysqli_fetch_assoc($result);

    // Check if the pet exists
    if (!$pet) {
        die("Pet not found!");
    }
} else {
    die("Pet ID is missing!");
}

// Handle form submission for updating the pet details
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $weight = $_POST['weight'];
    $image = $_FILES['image']['name'];

    // Image upload logic (if a new image is provided)
    if ($image) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    } else {
        // If no new image, keep the existing image
        $target_file = $pet['image'];
    }

    // Update the pet details in the database
    $update_query = "UPDATE pets SET name = '$name', species = '$species', breed = '$breed', age = '$age', gender = '$gender', weight = '$weight', image = '$target_file' WHERE pet_id = $pet_id";
    if (mysqli_query($conn, $update_query)) {
        header('Location: uploadpet.php'); // Redirect to pet list page after update
        exit();
    } else {
        echo "Error updating pet: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pet</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
/* General page styling */
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

/* Section styling */
.edit-pet-form {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 500px; /* Reduced max-width for a smaller container */
    text-align: center;
}

.edit-pet-form h2 {
    font-size: 24px;
    margin-bottom: 20px;
}

/* Form element styling */
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

label {
    font-weight: bold;
    text-align: left;
}

input[type="text"],
input[type="number"],
input[type="file"] {
    padding: 8px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 98%;
}

input[type="file"] {
    padding: 6px;
}

/* Button styling */
button[type="submit"] {
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
}

button[type="submit"]:hover {
    background-color: #45a049;
}

/* Link styling */
a {
    display: block;
    margin-top: 20px;
    color: #007BFF;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* Image preview styling */
img {
    max-width: 150px;
    margin-top: 10px;
    border-radius: 4px;
}
</style>

</head>
<body>
    <section class="edit-pet-form">
        <h2>Edit Pet Information</h2>
        <form action="edit_pet.php?pet_id=<?php echo $pet_id; ?>" method="POST" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($pet['name']); ?>" required>
            
            <label for="species">Species:</label>
            <input type="text" name="species" id="species" value="<?php echo htmlspecialchars($pet['species']); ?>" required>
            
            <label for="breed">Breed:</label>
            <input type="text" name="breed" id="breed" value="<?php echo htmlspecialchars($pet['breed']); ?>" required>
            
            <label for="age">Age:</label>
            <input type="number" name="age" id="age" value="<?php echo htmlspecialchars($pet['age']); ?>" required>
            
            <label for="gender">Gender:</label>
            <input type="text" name="gender" id="gender" value="<?php echo htmlspecialchars($pet['gender']); ?>" required>
            
            <label for="weight">Weight (kg):</label>
            <input type="number" name="weight" id="weight" value="<?php echo htmlspecialchars($pet['weight']); ?>" required>
            
            <label for="image">Pet Image:</label>
            <input type="file" name="image" id="image">
            
            <p>Current Image: <img src="<?php echo htmlspecialchars($pet['image']); ?>" alt="Pet Image"></p>
            
            <button type="submit" name="update">Update Pet</button>
        </form>
        <a href="uploadpet.php">Back to Pet List</a>
    </section>
</body>
</html>
