<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

// Fetch the username and email from the session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";
$email = isset($_SESSION['email']) ? $_SESSION['email'] : $_SESSION['email'];

// Build the query to include filters
$sql = "SELECT pets.*, users.phone FROM pets JOIN users ON pets.email = users.email WHERE pets.availability = 'available' AND pets.email != '$email'";

// Fetch pets from the database with optional filter
$species_filter = isset($_GET['species']) ? $_GET['species'] : '';
if ($species_filter) {
    $sql .= " AND species = '" . mysqli_real_escape_string($conn, $species_filter) . "'";
}

$result = mysqli_query($conn, $sql);
$pets = mysqli_fetch_all($result, MYSQLI_ASSOC);
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

                .help-item {
                    width: 90%;
                }
            }

            .filter-container {
            padding: 20px;
            background-color: #f9f9f9;
            text-align: center;
        }

        .filter-container form {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .filter-container select, .filter-container input {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .filter-container button {
            padding: 10px 20px;
            background-color: #000000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .filter-container button:hover {
            background-color: #333;
        }

        .pets-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .pet-card {
            width: 30%;
            max-width: 300px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            background-color: #6f899a;
        }

        .pet-card img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .pet-details {
            list-style: none;
            padding: 0;
            color: white;

        }

        .adopt-button {
            background-color: #443f3f;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }

        .adopt-button:hover {
            background-color: #333;
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

    .contact-button {
    background-color: #007BFF;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 5px;
    margin-top: 10px;
}

.contact-button:hover {
    background-color: #0056b3;
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

    const availablePets = pets.filter(pet => pet.availability === 'available');
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
        <img style="opacity: 70%;" src="userpagebg.png" alt="Background Image">
        <div class="text">
            <h1 style="size: 100%">BROWSE PETS</h1>    
        </div>
    </div>   

 <!-- Filter Section -->
    <section class="filter-container">
        <form action="user.php" method="GET">
            <!-- Species Filter -->
            <select name="species">
                <option value="">Select Species</option>
                <option value="Cat" <?php if ($species_filter == 'Cat') echo 'selected'; ?>>Cat</option>
                <option value="Dog" <?php if ($species_filter == 'Dog') echo 'selected'; ?>>Dog</option>
                <option value="Bird" <?php if ($species_filter == 'Bird') echo 'selected'; ?>>Bird</option>
                <option value="Rabbit" <?php if ($species_filter == 'Rabbit') echo 'selected'; ?>>Rabbit</option>
                <option value="Hamster" <?php if ($species_filter == 'Hamster') echo 'selected'; ?>>Hamster</option>
                <option value="Tortoise" <?php if ($species_filter == 'Tortoise') echo 'selected'; ?>>Tortoise</option>
                <option value="Other" <?php if ($species_filter == 'Other') echo 'selected'; ?>>Other</option>
            </select>
            <!-- Filter Button -->
            <button type="submit">Apply Filter</button>
        </form>
    </section>

 <!-- Browse Pets Section -->
 <section class="browse-pets">
        <div class="pets-grid">
            <?php foreach ($pets as $pet): ?>
                <div class="pet-card">
                    <img src="<?php echo htmlspecialchars($pet['image']); ?>" alt="<?php echo htmlspecialchars($pet['name']); ?>">
                    <ul class="pet-details">
                        <li><b>Name:</b> <?php echo htmlspecialchars($pet['name']); ?></li>
                        <li><b>Species:</b> <?php echo htmlspecialchars($pet['species']); ?></li>
                        <li><b>Breed:</b> <?php echo htmlspecialchars($pet['breed']); ?></li>
                        <li><b>Age:</b> <?php echo htmlspecialchars($pet['age']); ?> years</li>
                        <li><b>Gender:</b> <?php echo htmlspecialchars($pet['gender']); ?></li>
                        <li><b>Weight:</b> <?php echo htmlspecialchars($pet['weight']); ?> kg</li>
                        <br>
                        <li><b>Availability:</b> <?php echo htmlspecialchars($pet['availability']); ?></li>
                    </ul>
                    <?php if ($pet['email'] != $email): ?>
                        <button class="adopt-button" onclick="window.location.href = 'adopt_form.php?pet_id=<?php echo $pet['pet_id']; ?>'">
                            ADOPT ME
                        </button>
                        <button class="contact-button" onclick="window.location.href = 'https://wa.me/6<?php echo htmlspecialchars($pet['phone']); ?>?text=I%20am%20interested%20in%20your%20pet%20listing'">
                CONTACT OWNER
            </button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <br>

        

        <!-- Back to Top Button -->
        <div class="back-to-top" onclick="scrollToTop()">&#8679;</div>
        <?php include 'footer.php'; ?>
    </body>
</html>
