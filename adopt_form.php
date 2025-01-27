<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

// Fetch the username and other details from the session or database
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";
$email = isset($_SESSION['email']) ? $_SESSION['email'] : ''; // Assume email is stored in session
$phone = isset($_SESSION['phone']) ? $_SESSION['phone'] : ''; // Assume phone is stored in session

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch form data
    $pet_id = $_POST['pet_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Insert into the adoptions table using email
    $sql = "INSERT INTO adoptions (pet_id, full_name, email, phone, application_status, payment_status) VALUES ('$pet_id', '$full_name', '$email', '$phone', 'pending', 'pending')";
    if (mysqli_query($conn, $sql)) {
        // Redirect to status page after successful insertion
        header("Location: status.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch pet details (Assuming you get the pet ID from the URL or session)
$pet_id = $_GET['pet_id'] ?? 1; // Default to pet ID 1 if not provided
$sql = "SELECT * FROM pets WHERE pet_id = '$pet_id'";
$result = mysqli_query($conn, $sql);
$pet = mysqli_fetch_assoc($result);
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
            }

            nav.mask {
                display: flex;
                justify-content: space-around;
                align-items: center;
                padding: 10px 10px;
                opacity: 70%;
                background-color: #000000;
                color: white;
                position: fixed;
                top: 0;
                width: 100%;
                z-index: 1000;
            }

            nav.mask a {
                color: white;
                text-decoration: none;
                font-weight: bold;
            }

            nav.mask a:hover {
                text-decoration: underline;
            }

            .list {
                list-style: none;
                display: flex;
                gap: 15px;
            }

            .list li a {
                text-decoration: none;
                color: white;
            }

            .nav-buttons {
                display: flex;
                gap: 10px;
            }

            button {
                background-color: #000000;
                color: white;
                border: none;
                padding: 10px 15px;
                cursor: pointer;
                border-radius: 5px;
                transition: background-color 0.3s ease;
            }

            button:hover {
                background-color: #333;
            }

            .image-container {
                position: relative;
                text-align: center;
                color: white;
            }

            .image-container img {
                width: 100%;
                height: auto;
            }

            .image-container .text {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: rgba(255, 255, 255, 0.8);
                padding: 20px;
                border-radius: 10px;
                text-align: center;
                color: #333;
            }

            .image-container .text button {
                background-color: #000000;
                color: white;
                border: none;
                padding: 10px 20px;
                margin-top: 20px;
                cursor: pointer;
                font-size: 16px;
                border-radius: 5px;
                transition: background-color 0.3s ease;
            }

            .image-container .text button:hover {
                background-color: #333;
            }

            .how-we-help {
                padding: 50px 20px;
                background-color: #f9f9f9;
                text-align: center;
            }

            .how-we-help h2 {
                font-size: 36px;
                margin-bottom: 30px;
                color: #333;
            }

            .help-items {
                display: flex;
                justify-content: center;
                gap: 20px;
                flex-wrap: wrap;
            }

            .help-item {
                text-align: center;
                width: 30%;
                max-width: 300px;
            }

            .help-item img {
                width: 100%;
                height: auto;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }

            .help-item p {
                margin-top: 10px;
                font-size: 16px;
                color: #555;
            }

            .contact-section {
                background-color: #6F899A;
                color: white;
                text-align: center;
                padding: 30px 30px;
            }

            .contact-section h3 {
                margin-bottom: 10px;
                font-size: 24px;
            }

            .contact-section form {
                margin-top: 20px;
            }

            .contact-section form input,
            .contact-section form textarea,
            .contact-section form button {
                display: block;
                width: 100%;
                max-width: 500px;
                margin: 10px auto;
                padding: 10px;
                font-size: 16px;
                border: none;
                border-radius: 5px;
            }

            .contact-section form input,
            .contact-section form textarea {
                background-color: #fff;
                color: #333;
            }

            .contact-section form button {
                background-color: #000000;
                color: white;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .contact-section form button:hover {
                background-color: #333;
            }

            .upload-pet-section {
                padding: 50px 20px;
                background-color: #f9f9f9;
                text-align: center;
            }

            .upload-pet-section h2 {
                font-size: 36px;
                margin-bottom: 30px;
                color: #333;
            }

            .upload-pet-form {
                background-color: #fff;
                padding: 30px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
                display: inline-block;
                width: 100%;
                max-width: 600px;
                text-align: left;
            }

            .upload-pet-form input,
            .upload-pet-form select,
            .upload-pet-form textarea,
            .upload-pet-form button {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 16px;
            }

            .upload-pet-form button {
                background-color: #000000;
                color: white;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .upload-pet-form button:hover {
                background-color: #333;
            }

            .back-to-top {
                position: fixed;
                bottom: 20px;
                right: 20px;
                background-color: #000000;
                color: white;
                padding: 10px 15px;
                border-radius: 50%;
                font-size: 18px;
                cursor: pointer;
                display: none;
                z-index: 1000;
            }

            .back-to-top:hover {
                background-color: #333;
            }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f9f9f9;
        }
        .form-container {
            width: 100%;
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #000000;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #333;
        }
        .back-btn {
            background-color: #ddd;
        }
        .back-btn:hover {
            background-color: #bbb;
        }

        .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-toggle {
        cursor: pointer;
        color: white;
        text-decoration: none;
        font-weight: bold;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        background-color: white;
        min-width: 150px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        border-radius: 5px;
    }

    .dropdown-menu li {
        list-style: none;
        padding: 10px;
        text-align: left;
    }

    .dropdown-menu li a {
        text-decoration: none;
        color: #333;
        display: block;
        width: 100%;
    }

    .dropdown-menu li a:hover {
        background-color: #f0f0f0;
    }
    </style>
    <script>
            function confirmSubmission(event) {
                event.preventDefault();
                const confirmation = confirm("Are you sure you want to send this message?");
                if (confirmation) {
                    event.target.submit();
                }
            }

            function scrollToTop() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            window.addEventListener('scroll', () => {
                const backToTopButton = document.querySelector('.back-to-top');
                if (window.scrollY > 300) {
                    backToTopButton.style.display = 'block';
                } else {
                    backToTopButton.style.display = 'none';
                }
            });


            function toggleDropdown() {
        const dropdownMenu = document.getElementById('dropdown-menu');
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    }

    // Close the dropdown if clicked outside
    window.addEventListener('click', (event) => {
        const dropdownMenu = document.getElementById('dropdown-menu');
        if (!event.target.matches('.dropdown-toggle')) {
            dropdownMenu.style.display = 'none';
        }
    });

  
        </script>
</head>
<body>
<nav class="mask">
            <ul class="list">
            <li><a href="user.php">Browse Pets</a></li>
                <a href="#"></a>
                <li><a href="uploadpet.php">Upload A Pet</a></li>
                <a href="#"></a>
                <li><a href="status.php">Application Status</a></li>
                <a href="#"></a>
                <li><a href="monthly_income.php">Monthly Income</a></li>
                <a href="#"></a>
                <li><a href="contactus.php">Contact Us</a></li>
                <a href="#"></a>
                <li><a href="termsandcondition_user.php">Terms & Conditions</a></li>
                <a href="#"></a>

            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>

            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>

            
                <!-- Username Dropdown Menu -->
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" onclick="toggleDropdown()">
                Name: <?php echo htmlspecialchars($username); ?>
            </a>
            <ul class="dropdown-menu" id="dropdown-menu">
                <li><a href="edit_profile.php">Edit Profile</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </li>
            </ul>
        </nav>
<!-- Hero Section -->
<div class="image-container">
    <img style="opacity: 70%;" src="userpagebg.png" alt="Background Image">
    <div class="text">
        <h1>ADOPTION FORM</h1>
    </div>
</div>

<div class="form-container">
    <form action="adopt_form.php" method="POST">
        <h2>Adoption Form</h2>

        <!-- Pet Information Section -->
        <h3>Pet Information</h3>
        <label for="pet_name">Name:</label>
        <input type="text" id="name" name="pet_name" value="<?php echo htmlspecialchars($pet['name']); ?>" readonly>

        <label for="species">Species:</label>
        <input type="text" id="species" name="species" value="<?php echo htmlspecialchars($pet['species']); ?>" readonly>

        <label for="breed">Breed:</label>
        <input type="text" id="breed" name="breed" value="<?php echo htmlspecialchars($pet['breed']); ?>" readonly>

        <label for="age">Age:</label>
        <input type="text" id="age" name="age" value="<?php echo htmlspecialchars($pet['age']); ?>" readonly>

        <label for="gender">Gender:</label>
        <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($pet['gender']); ?>" readonly>

        <label for="weight">Weight:</label>
        <input type="text" id="weight" name="weight" value="<?php echo htmlspecialchars($pet['weight']); ?>" readonly>

        <!-- User Information Section -->
        <h3>User Information</h3>
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($username); ?>" required>

        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>

        <label for="phone">Phone Number:</label>
        <input type="phone" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" readonly>


        <!-- Hidden Input for Pet ID -->
        <input type="hidden" name="pet_id" value="<?php echo htmlspecialchars($pet_id); ?>">

        <!-- Submit Button -->
        <button type="submit">Submit for Adoption</button>
        <a href="user.php"><button type="button" class="back-btn">Back</button></a>
    </form>

    <!-- Adoption Fee -->
    <p style="text-align: center; margin-top: 20px; font-size: 18px; font-weight: bold;">Adoption Fee: RM200</p>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
