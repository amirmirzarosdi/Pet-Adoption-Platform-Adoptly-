<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

// Fetch the username from the session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";

// Fetch pets and their owner's details from the database
$query = "SELECT pets.*, users.email, users.phone FROM pets JOIN users ON pets.email = users.email";
$result = mysqli_query($conn, $query);

// Handle form submissions for updates, deletions, and adding new pets
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        // Update pet info
        $pet_id = $_POST['pet_id'];
        $name = $_POST['name'];
        $species = $_POST['species'];
        $breed = $_POST['breed'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $weight = $_POST['weight'];
        $availability = $_POST['availability'];
        $update_query = "UPDATE pets SET name='$name', species='$species', breed='$breed', age='$age', gender='$gender', weight='$weight', availability='$availability' WHERE pet_id='$pet_id'";
        mysqli_query($conn, $update_query);
    } elseif (isset($_POST['delete'])) {
        // Delete pet
        $pet_id = $_POST['pet_id'];
        $delete_query = "DELETE FROM pets WHERE pet_id='$pet_id'";
        mysqli_query($conn, $delete_query);
    } elseif (isset($_POST['add'])) {
        // Add a new pet
        $name = $_POST['name'];
        $species = $_POST['species'];
        $breed = $_POST['breed'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $weight = $_POST['weight'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $availability = $_POST['availability'];

        // Handle image upload
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["pet_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an image
        $check = getimagesize($_FILES["pet_image"]["tmp_name"]);
        if ($check !== false) {
            // Allow certain file formats
            $allowed_formats = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($imageFileType, $allowed_formats)) {
                if (move_uploaded_file($_FILES["pet_image"]["tmp_name"], $target_file)) {
                    // Insert pet details into the database
                    $add_query = "INSERT INTO pets (name, species, breed, age, gender, weight, email, phone, availability, image, created_at) VALUES ('$name', '$species', '$breed', '$age', '$gender', '$weight', '$email', '$phone', '$availability', '$target_file', NOW())";
                    mysqli_query($conn, $add_query);
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
        } else {
            echo "File is not an image.";
        }
    }
    // Refresh the page to reflect changes
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT pets.*, users.email, users.phone FROM pets JOIN users ON pets.email = users.email WHERE pets.name LIKE '%$search%' OR pets.species LIKE '%$search%' OR pets.breed LIKE '%$search%'";
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

    .pet-list {
            max-width: 1500px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .pet-list table {
            width: 100%;
            border-collapse: collapse;
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
        .pet-list td input {
            width: 100%;
            padding: 5px;
            box-sizing: border-box;
        }
        .actions button {
            padding: 5px 10px;
            margin: 2px;
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .actions button.delete {
            background-color: #e74c3c;
        }
        .actions button:hover {
            opacity: 0.8;
        }
        .add-pet {
            margin-bottom: 20px;
            text-align: center;
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

function searchTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.querySelector('table');
    const tr = table.getElementsByTagName('tr');
    for (let i = 1; i < tr.length; i++) {
        const td = tr[i].getElementsByTagName('td')[0];
        if (td) {
            const txtValue = td.textContent || td.innerText;
            tr[i].style.display = txtValue.toLowerCase().indexOf(filter) > -1 ? '' : 'none';
        }
    }
}
function confirmDeletion(event) {
    if (!confirm("Are you sure you want to delete this pet?")) {
        event.preventDefault(); // Prevent form submission if the user cancels
    }
}
        </script>
    </head>
    <body>
    <nav class="mask">
            <ul class="list">
                <li><a href="adminpage.php">Main Page</a></li>
                <a href="#"></a>
                <li><a href="petlist.php">Pet List</a></li>
                <a href="#"></a>
                <li><a href="user_list.php">User List</a></li>
                <a href="#"></a>
                <li><a href="users_income.php">Website & User Income</a></li>
                <a href="#"></a>
                <li><a href="admincontact.php">Users Contact</a></li>
                <a href="#"></a>
                <li><a href="adminpetcare.php">Add Petcare Resources</a></li>
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
                <?php echo htmlspecialchars($username); ?> Page
            </a>
            <ul class="dropdown-menu" id="dropdown-menu">
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </li>
            </ul>
        </nav>

        <!-- Hero Section -->
        <div class="image-container">
        <img style="opacity: 70%;" src="userpagebg.png" alt="Background Image">
        <div class="text">
            <h1 style="size: 100%">PET LIST</h1>    
        </div>
    </div>   

    <div class="pet-list">
    <h2>All Pets</h2>
    <div class="search-bar">
    <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for pets...">
</div>

    <!-- Add Pet Form -->
<div class="add-pet">
    <form method="POST" enctype="multipart/form-data" onsubmit="return confirmAddPet()">
        <table>
            <tr>
                <td><input type="text" name="name" placeholder="Pet Name" required></td>
                <td>
                    <select name="species" required>
                        <option value="">Select Species</option>
                        <option value="Cat">Cat</option>
                        <option value="Dog">Dog</option>
                        <option value="Bird">Bird</option>
                        <option value="Rabbit">Rabbit</option>
                        <option value="Hamster">Hamster</option>
                        <option value="Tortoise">Tortoise</option>
                        <option value="Other">Other</option>
                    </select>
                </td>
                <td><input type="text" name="breed" placeholder="Breed" required></td>
                <td><input type="number" name="age" placeholder="Age" required></td>
                <td>
                    <select name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </td>
                <td><input type="number" name="weight" placeholder="Weight" required></td>
                <td><input type="email" name="email" placeholder="Owner Email" required></td>
                <td><input type="text" name="phone" placeholder="Owner Phone" required></td>
                <td><input type="text" name="availability" placeholder="Availability" required></td>
                <td><input type="file" name="pet_image" required></td>
                <td><button type="submit" name="add">Add Pet</button></td>
            </tr>
        </table>
    </form>
</div>


<script>
function confirmAddPet() {
    return confirm("Are you sure you want to add this pet?");
}
</script>

    <!-- Pet List Table -->
    <table>
        <thead>
            <tr>
                <th>Pet Name</th>
                <th>Species</th>
                <th>Breed</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Weight</th>
                <th>Owner Email</th>
                <th>Owner Phone</th>
                <th>Availability</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<form method='POST'>";
        echo "<tr>";
        echo "<td><img src='" . htmlspecialchars($row['image']) . "' alt='Pet Image' style='max-width: 100px; height: auto;'></td>";
        echo "<td><input type='text' name='name' value='" . htmlspecialchars($row['name']) . "'></td>";
        echo "<td><input type='text' name='species' value='" . htmlspecialchars($row['species']) . "'></td>";
        echo "<td><input type='text' name='breed' value='" . htmlspecialchars($row['breed']) . "'></td>";
        echo "<td><input type='number' name='age' value='" . htmlspecialchars($row['age']) . "'></td>";
        echo "<td><input type='text' name='gender' value='" . htmlspecialchars($row['gender']) . "'></td>";
        echo "<td><input type='number' name='weight' value='" . htmlspecialchars($row['weight']) . "'></td>";
        echo "<td><input type='email' name='email' value='" . htmlspecialchars($row['email']) . "' readonly></td>";
        echo "<td><input type='text' name='phone' value='" . htmlspecialchars($row['phone']) . "' readonly></td>";
        echo "<td><input type='text' name='availability' value='" . htmlspecialchars($row['availability']) . "'></td>";
        echo "<td class='actions'>";
        echo "<input type='hidden' name='pet_id' value='" . $row['pet_id'] . "'>";
        echo "<button type='submit' name='update'>Update</button>";
        echo "<button type='submit' name='delete' class='delete' onclick='confirmDeletion(event)'>Delete</button>";
        echo "</td>";
        echo "</tr>";
        echo "</form>";
    }
} else {
    echo "<tr><td colspan='11'>No pets found.</td></tr>";
} ?>

            </tbody>
    </table>
</div>
 
        <!-- Back to Top Button -->
        <div class="back-to-top" onclick="scrollToTop()">&#8679;</div>
        <?php include 'footer.php'; ?>
    </body>
</html>
