<?php
// Include the database connection
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs
    $petName = mysqli_real_escape_string($conn, $_POST['petName']);
    $petSpecies = mysqli_real_escape_string($conn, $_POST['petSpecies']);
    $petBreed = mysqli_real_escape_string($conn, $_POST['petBreed']);
    $petAge = (int) $_POST['petAge'];
    $petGender = mysqli_real_escape_string($conn, $_POST['petGender']);
    $petWeight = (float) $_POST['petWeight'];

    // Handle the uploaded image
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["petImage"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an image
    $check = getimagesize($_FILES["petImage"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        exit;
    }

    // Allow certain file formats
    $allowed_formats = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_formats)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        exit;
    }

    // Upload the image
    if (move_uploaded_file($_FILES["petImage"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars(basename($_FILES["petImage"]["name"])) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
        exit;
    }

    // Prepare SQL query to insert the pet data into the database
    $sql = "INSERT INTO pets (pet_name, pet_species, pet_breed, pet_age, pet_gender, pet_weight, pet_image)
            VALUES ('$petName', '$petSpecies', '$petBreed', $petAge, '$petGender', $petWeight, '$target_file')";

    if ($conn->query($sql) === TRUE) {
        echo "Pet information successfully submitted!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
