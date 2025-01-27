<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

// Fetch the username and email from the session
$email = isset($_SESSION['email']) ? $_SESSION['email'] : "";
$name = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";

if (!empty($email)) {
    $query = "SELECT name, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $username = $user['name'];
        $email = $user['email'];
    }
}
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
    .contact-section {
    background-color: #6F899A;
    color: white;
    text-align: center;
    padding: 40px 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.contact-section h3 {
    margin-bottom: 20px;
    font-size: 28px;
    font-weight: bold;
}

.contact-section form input,
.contact-section form textarea,
.contact-section form button {
    display: block;
    width: 90%;
    max-width: 500px;
    margin: 15px auto;
    padding: 12px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
            <img style="opacity: 70%;" src="contact.png" alt="Background Image">
            <div class="text">
                <section class="contact-section">
            <h3>Contact Us</h3>
            <form action="submit_contact.php" method="POST" onsubmit="confirmSubmission(event)">
    <input type="text" name="name" value="<?php echo htmlspecialchars($username); ?>" placeholder="Your Name" required>
    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Your Email" required>
    <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
    <button type="submit">Send</button>
</form>

        </section>
            </div>
        </div>
        

        <!-- Back to Top Button -->
        <div class="back-to-top" onclick="scrollToTop()">&#8679;</div>
        <?php include 'footer.php'; ?>
    </body>
</html>
