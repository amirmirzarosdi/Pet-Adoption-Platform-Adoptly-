<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

// Fetch the username and email from the session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";

// Fetch data for pets
$pets_query = "SELECT species, COUNT(*) as count FROM pets GROUP BY species";
$pets_result = mysqli_query($conn, $pets_query);
$pets_data = [];
while ($row = mysqli_fetch_assoc($pets_result)) {
    $pets_data[] = $row;
}

// Fetch data for users
$users_query = "SELECT role, COUNT(*) as count FROM users GROUP BY role";
$users_result = mysqli_query($conn, $users_query);
$users_data = [];
while ($row = mysqli_fetch_assoc($users_result)) {
    $users_data[] = $row;
}

// Fetch data for income
$income_query = "SELECT DATE_FORMAT(payment_date, '%Y-%m') as month, SUM(amount - 50) as total FROM payments GROUP BY month";
$income_result = mysqli_query($conn, $income_query);
$income_data = [];
if ($income_result) {
    while ($row = mysqli_fetch_assoc($income_result)) {
        $income_data[] = $row;
    }
}



// Fetch data for contacts
$contacts_query = "SELECT message, COUNT(*) as count FROM contacts GROUP BY message";
$contacts_result = mysqli_query($conn, $contacts_query);
$contacts_data = [];
while ($row = mysqli_fetch_assoc($contacts_result)) {
    $contacts_data[] = $row;
}

mysqli_close($conn);
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
    .chart-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            padding: 20px;
        }

        .chart {
            width: 45%;
            height: 300px;
            background: white;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .chart {
                width: 100%;
            }
        }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <img style="opacity: 70%;" src="admin.png" alt="Background Image">
        <div class="text">
            <h1 style="size: 100%">ADMIN PAGE</h1>    
        </div>
    </div>   

    <div class="chart-container">
        <div class="chart">
            <canvas id="petsChart"></canvas>
        </div>
        <div class="chart">
            <canvas id="usersChart"></canvas>
        </div>
        <div class="chart">
            <canvas id="incomeChart"></canvas>
        </div>
        <div class="chart">
            <canvas id="contactsChart"></canvas>
        </div>
    </div>

    <script>
        // Data for pets chart
        const petsData = <?php echo json_encode($pets_data); ?>;
        const petsLabels = petsData.map(data => data.species);
        const petsCounts = petsData.map(data => data.count);

        new Chart(document.getElementById('petsChart'), {
            type: 'bar',
            data: {
                labels: petsLabels,
                datasets: [{
                    label: 'Number of Pets',
                    data: petsCounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: { responsive: true }
        });

        // Data for users chart
        const usersData = <?php echo json_encode($users_data); ?>;
        const usersLabels = usersData.map(data => data.role);
        const usersCounts = usersData.map(data => data.count);

        new Chart(document.getElementById('usersChart'), {
            type: 'bar',
            data: {
                labels: usersLabels,
                datasets: [{
                    label: 'Number of Users',
                    data: usersCounts,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: { responsive: true }
        });

        // Data for income chart
const incomeData = <?php echo json_encode($income_data); ?>;
const months = incomeData.map(data => data.month); // Get unique months
const totals = incomeData.map(data => data.total); // Get total income for each month

new Chart(document.getElementById('incomeChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Total Income',
            data: totals,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true
    }
});


        // Data for contacts chart
        const contactsData = <?php echo json_encode($contacts_data); ?>;
        const contactsLabels = contactsData.map(data => data.message);
        const contactsCounts = contactsData.map(data => data.count);

        new Chart(document.getElementById('contactsChart'), {
            type: 'bar',
            data: {
                labels: contactsLabels,
                datasets: [{
                    label: 'Number of Contacts',
                    data: contactsCounts,
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }]
            },
            options: { responsive: true }
        });
    </script>

        <!-- Back to Top Button -->
        <div class="back-to-top" onclick="scrollToTop()">&#8679;</div>
        <?php include 'footer.php'; ?>
    </body>
</html>
