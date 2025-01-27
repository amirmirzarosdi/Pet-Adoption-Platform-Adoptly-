<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

// Fetch the username and email from the session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";
$email = isset($_SESSION['email']) ? $_SESSION['email'] : $_SESSION['email'];
$phone = isset($_SESSION['phone']) ? $_SESSION['phone'] : $_SESSION['phone'];

// Handle form submission for pet upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'upload') {
        // Fetch form data
        $pet_name = mysqli_real_escape_string($conn, $_POST['pet_name']);
        $species = mysqli_real_escape_string($conn, $_POST['species']);
        $breed = mysqli_real_escape_string($conn, $_POST['breed']);
        $age = mysqli_real_escape_string($conn, $_POST['age']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $weight = mysqli_real_escape_string($conn, $_POST['weight']);

        // Handle image upload
        $image_name = $_FILES['pet_image']['name'];
        $image_tmp_name = $_FILES['pet_image']['tmp_name'];
        $image_folder = "uploads/" . $image_name;

        // Check if file upload was successful
        if (move_uploaded_file($image_tmp_name, $image_folder)) {
            // Insert pet details into the pets table using email
            $sql_pets = "INSERT INTO pets (name, species, breed, age, gender, weight, image, email, phone) VALUES ('$pet_name', '$species', '$breed', '$age', '$gender', '$weight', '$image_folder', '$email', '$phone')";
            if (mysqli_query($conn, $sql_pets)) {
                echo "<script>alert('Pet uploaded successfully!');</script>";
            } else {
                echo "<script>alert('Failed to upload pet.');</script>";
            }
        } else {
            echo "<script>alert('Failed to upload image.');</script>";
        }
    }
}

// Fetch the pets uploaded by the current user
$query = "SELECT * FROM pets WHERE email = '$email'";
$result = mysqli_query($conn, $query);
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

            @media (max-width: 768px) {
                .help-items {
                    flex-direction: column;
                    align-items: center;
                }

                .help-item {
                    width: 90%;
                }
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
    .form-container {
    max-width: 800px;
    margin: 100px auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.form-container table {
    width: 100%;
    border-collapse: collapse;
}

.form-container th, .form-container td {
    padding: 10px;
    text-align: left;
    vertical-align: middle;
}

.form-container th {
    background: #f4f4f4;
    border-bottom: 2px solid #ddd;
}

.form-container td {
    border-bottom: 1px solid #ddd;
}

.form-container input, .form-container select, .form-container button {
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

.form-container button {
    background-color: #000000;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.form-container button:hover {
    background-color: #333;
}

.pet-list {
    width: 80%;
    margin: 30px auto;
    font-family: Arial, sans-serif;
    color: #333;
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.pet-list h2 {
    text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
    color: #2c3e50;
}

.pet-list table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.pet-list th, .pet-list td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.pet-list th {
    background-color: #3498db;
    color: white;
}

.pet-list td {
    background-color: #f9f9f9;
}

.pet-list td img {
    max-width: 100px;
    height: auto;
    border-radius: 8px;
}

.pet-list td a {
    color: #3498db;
    text-decoration: none;
    font-weight: bold;
}

.pet-list td a:hover {
    text-decoration: underline;
}

.pet-list tr:nth-child(even) td {
    background-color: #f1f1f1;
}

.pet-list tr:hover td {
    background-color: #f2f2f2;
    cursor: pointer;
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
            <a class="dropdown-toggle" onclick="toggleDropdown()">
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
            <img style="opacity: 70%;" src="upload.png" alt="Background Image">
            <div class="text">
                <h1 style="size: 100%">UPLOAD PETS</h1>    
            </div>
        </div>   

        <div class="form-container">
    <h2>Upload a Pet</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="upload">
        <table>
            <tr>
                <th>Field</th>
                <th>Input</th>
            </tr>
            <tr>
                <td>Pet Image</td>
                <td><input type="file" name="pet_image" required></td>
            </tr>
            <tr>
                <td>Pet Name</td>
                <td><input type="text" name="pet_name" required></td>
            </tr>
            <tr>
                <td>Species</td>
                <td>
                    <select name="species" required>
                        <option value="cat">Cat</option>
                        <option value="dog">Dog</option>
                        <option value="bird">Bird</option>
                        <option value="bird">Rabbit</option>
                        <option value="bird">Hamster</option>
                        <option value="bird">Tortoise</option>
                        <option value="other">Other</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Breed</td>
                <td><input type="text" name="breed" required></td>
            </tr>
            <tr>
                <td>Age</td>
                <td><input type="number" name="age" required></td>
            </tr>
            <tr>
                <td>Gender</td>
                <td>
                    <select name="gender" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Weight (kg)</td>
                <td><input type="number" step="0.1" name="weight" required></td>
            </tr>
        </table>
        <button type="submit">Submit</button>
    </form>
</div>

<!-- New Section for Displaying User's Pets -->
<section class="pet-list">
    <h2>Your Submitted Pets</h2>
    <table>
        <tr>
            <th>Pet Image</th>
            <th>Name</th>
            <th>Species</th>
            <th>Breed</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Weight</th>
            <th>Actions</th>
            <th>Availability</th>
        </tr>
        <?php while ($pet = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><img src="<?php echo htmlspecialchars($pet['image']); ?>" alt="Pet Image"></td>
            <td><?php echo htmlspecialchars($pet['name']); ?></td>
            <td><?php echo htmlspecialchars($pet['species']); ?></td>
            <td><?php echo htmlspecialchars($pet['breed']); ?></td>
            <td><?php echo htmlspecialchars($pet['age']); ?></td>
            <td><?php echo htmlspecialchars($pet['gender']); ?></td>
            <td><?php echo htmlspecialchars($pet['weight']); ?> kg</td>
            <td>
                <a href="edit_pet.php?pet_id=<?php echo $pet['pet_id']; ?>">Edit</a> | 
                <a href="delete_pet.php?pet_id=<?php echo $pet['pet_id']; ?>" onclick="return confirm('Are you sure you want to delete this pet?')">Delete</a>
            </td>
            <td><?php echo htmlspecialchars($pet['availability']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</section>
<?php include 'footer.php'; ?>
</body>
</html>
