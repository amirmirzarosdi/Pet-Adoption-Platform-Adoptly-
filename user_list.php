<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

// Fetch the username and email from the session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";

// Fetch users from the database
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM users WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
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

    .user-list-section {
    padding: 40px 20px;
    background-color: #ffffff;
    margin: 20px auto;
    width: 80%;
    max-width: 1000px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.user-list-section h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

.user-list-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0 auto;
}

.user-list-table th, .user-list-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.user-list-table th {
    background-color: #3498db;
    color: white;
}

.user-list-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.user-list-table tr:hover {
    background-color: #f2f2f2;
}

.user-list-table td a {
    color: #3498db;
    text-decoration: none;
    font-weight: bold;
}

.user-list-table td a:hover {
    text-decoration: underline;
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
            <h1 style="size: 100%">USER LIST</h1>    
        </div>
    </div>   

    <section class="user-list-section">
    <h1>User List</h1>
    <form method="GET" action="user_list.php">
        <input type="text" name="search" placeholder="Search users...">
        <button type="submit">Search</button>
    </form>
    <br>
    <table class="user-list-table" border="1">
        <tr>
            <th>Email</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
        <?php while ($user = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars($user['name']); ?></td>
            <td><?php echo htmlspecialchars($user['phone']); ?></td>
            <td><a href="edit_user.php?id=<?php echo $user['email']; ?>">Edit</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</section>

        <!-- Back to Top Button -->
        <div class="back-to-top" onclick="scrollToTop()">&#8679;</div>
        <?php include 'footer.php'; ?>
    </body>
</html>
