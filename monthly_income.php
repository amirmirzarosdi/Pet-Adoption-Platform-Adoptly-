<?php
session_start();
include 'db_connection.php';

$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";
$email = $_SESSION['email'];

// Fetch monthly income data for the logged-in user (RM150 per adoption)
$sql_income = "SELECT DATE_FORMAT(p.payment_date, '%Y-%m') as month, SUM(p.amount - 50) as total_income 
               FROM payments p
               JOIN pets pt ON p.pet_id = pt.pet_id
               WHERE pt.email = '$email'
               GROUP BY month 
               ORDER BY month";
$result_income = mysqli_query($conn, $sql_income);

$months = [];
$incomes = [];

if ($result_income) {
    while ($row = mysqli_fetch_assoc($result_income)) {
        $months[] = $row['month'];
        $incomes[] = $row['total_income'];
    }
} else {
    echo "Error fetching income data: " . mysqli_error($conn);
}

// Fetch pet data for the logged-in user
$sql_pets = "SELECT name, availability, 'RM200' as price FROM pets WHERE email = '$email'";
$result_pets = mysqli_query($conn, $sql_pets);

$pets = [];
$total_pets = 0;
$total_available_pets = 0;
$total_unavailable_pets = 0;

if ($result_pets) {
    while ($row = mysqli_fetch_assoc($result_pets)) {
        $pets[] = $row;
        $total_pets++;
        if ($row['availability'] == 'available') {
            $total_available_pets++;
        } else {
            $total_unavailable_pets++;
        }
    }
} else {
    echo "Error fetching pet data: " . mysqli_error($conn);
}

// Fetch approved and paid adoptions
$sql_adoptions = "SELECT COUNT(*) as total_approved FROM adoptions WHERE application_status = 'approved' AND payment_status = 'paid'";
$result_adoptions = mysqli_query($conn, $sql_adoptions);
$total_approved = $result_adoptions ? mysqli_fetch_assoc($result_adoptions)['total_approved'] : 0;

$total_income = array_sum($incomes);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Income</title>
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

    .container {
            margin-top: 60px;
            padding: 20px;
            text-align: center;
        }

        .chart-container, .summary-container, .pets-container {
            width: 80%;
            max-width: 800px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        canvas {
            width: 100% !important;
            height: auto !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
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
        <img style="opacity: 70%;" src="income.png" alt="Background Image">
        <div class="text">
            <h1 style="size: 100%">Montly Income</h1>    
        </div>
    </div>
     
    <div class="container">
        <h1>Owner Earnings</h1>
        <div class="summary-container">
            <h2>Summary</h2>
            <table>
                <tr>
                    <th>Total Income</th>
                    <td>RM<?php echo number_format($total_income, 2); ?></td>
                </tr>
                <tr>
                    <th>Total Pets Uploaded</th>
                    <td><?php echo $total_pets; ?></td>
                </tr>
                <tr>
                    <th>Total Available Pets</th>
                    <td><?php echo $total_available_pets; ?></td>
                </tr>
                <tr>
                    <th>Total Unavailable Pets</th>
                    <td><?php echo $total_unavailable_pets; ?></td>
                </tr>
                <tr>
                    <th>Total Approved and Paid Adoptions</th>
                    <td><?php echo $total_approved; ?></td>
                </tr>
            </table>
        </div>

    <div class="chart-container">
        <canvas id="incomeChart"></canvas>
    </div>

    <div class="pets-container">
        <h2>Your Pets</h2>
        <input type="text" id="searchInput" onkeyup="searchPets()" placeholder="Search for pets..">
        <table id="petsTable">
            <thead>
                <tr>
                    <th>Pet Name</th>
                    <th>Availability</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pets as $pet): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pet['name']); ?></td>
                        <td><?php echo htmlspecialchars($pet['availability']); ?></td>
                        <td><?php echo $pet['price']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('incomeChart').getContext('2d');
    const incomeChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: 'Monthly Income',
                data: <?php echo json_encode($incomes); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    function searchPets() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('petsTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            const td = tr[i].getElementsByTagName('td')[0];
            if (td) {
                const txtValue = td.textContent || td.innerText;
                tr[i].style.display = txtValue.toLowerCase().indexOf(filter) > -1 ? '' : 'none';
            }
        }
    }
</script>
<?php include 'footer.php'; ?>
</body>
</html>
