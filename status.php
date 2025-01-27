<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

// Fetch the username and other details from the session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";

// Get the user's adoption details from the database
$sql = "SELECT adoptions.*, pets.name AS pet_name 
        FROM adoptions 
        LEFT JOIN pets ON adoptions.pet_id = pets.pet_id
        WHERE adoptions.full_name = '$username'";

$result = mysqli_query($conn, $sql);

// Update application status based on payment status
if (isset($_GET['adoption_id'])) {
    $adoption_id = $_GET['adoption_id'];

    // Check payment status
    $payment_check_query = "SELECT payment_status FROM adoptions WHERE adoption_id = '$adoption_id'";
    $payment_check_result = mysqli_query($conn, $payment_check_query);

    if ($payment_check_result && mysqli_num_rows($payment_check_result) > 0) {
        $payment_status = mysqli_fetch_assoc($payment_check_result)['payment_status'];

        // Update application status if payment is PAID
        if ($payment_status === 'PAID') {
            $update_status_query = "UPDATE adoptions SET application_status = 'Approved' WHERE adoption_id = '$adoption_id'";
            mysqli_query($conn, $update_status_query);
        }
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
        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
            text-align: center;
        }
        th, td {
            padding: 10px;
        }
        th {
            background-color: #000000;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .status-header {
            text-align: center;
            margin-top: 50px;
            font-size: 24px;
        }
        .back-btn {
            background-color: #ddd;
            text-align: center;
            display: block;
            padding: 10px;
            width: 200px;
            margin: 20px auto;
            text-decoration: none;
            border-radius: 5px;
            color: black;
        }
        .back-btn:hover {
            background-color: #bbb;
        }

        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
            text-align: center;
        }
        th, td {
            padding: 10px;
        }
        th {
            background-color: #000000;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .status-header {
            text-align: center;
            margin-top: 50px;
            font-size: 24px;
        }
        .back-btn {
            background-color: #ddd;
            text-align: center;
            display: block;
            padding: 10px;
            width: 200px;
            margin: 20px auto;
            text-decoration: none;
            border-radius: 5px;
            color: black;
        }
        .back-btn:hover {
            background-color: #bbb;
        }
        .pay-btn {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .pay-btn:hover {
            background-color: #45a049;
        }

        .print-btn {
            background-color: #FF9800;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .print-btn:hover {
            background-color: #FF8F00;
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
    <script>
            function printReceipt(adoptionId) {
    var receiptWindow = window.open('', '_blank', 'width=800,height=600');
    receiptWindow.document.write('<html><head><title>Receipt</title></head><body>');

    // Fetch the adoption details from the database
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_receipt.php?adoption_id=' + adoptionId, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Display the receipt details in the new window
            receiptWindow.document.write(xhr.responseText);
            receiptWindow.document.write('<div style="text-align: center; margin-top: 20px;">');
            receiptWindow.document.write('<button style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;" onclick="window.print();">Print</button>');
            receiptWindow.document.write('</div>');
        }
    };
    xhr.send();

    receiptWindow.document.write('</body></html>');
    receiptWindow.document.close();
}

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
        <img style="opacity: 70%;" src="status.png" alt="Background Image">
        <div class="text">
            <h1 style="size: 100%">APPLICATION STATUS</h1>   
            <h3 style="size: 100%">* Make sure to pay the adopion fee before contacting the owner for further process *</h3>    
        </div>
        
    </div>   


<!-- Status Table -->
<div class="status-header">
        <h2>Adoption Application Status</h2>
    </div>

    <table>
    <thead>
    <tr>
        <th>Pet Name</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Application Status</th>
        <th>Payment Status</th>
        <th>Action</th>
        <th>Print Receipt</th>
        <th>Contact Owner</th> <!-- New column for Contact Owner -->
    </tr>
</thead>

<tbody>
    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['pet_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
            echo "<td>" . htmlspecialchars($row['application_status']) . "</td>";
            echo "<td>" . htmlspecialchars($row['payment_status']) . "</td>";

            if ($row['payment_status'] != 'paid' && $row['application_status'] != 'Approved') {
                echo "<td><a href='payment.php?adoption_id=" . $row['adoption_id'] . "' class='pay-btn'>Pay</a></td>";
            } else {
                echo "<td>Paid</td>";
            }

            echo "<td><a href='#' onclick='printReceipt(" . $row['adoption_id'] . ")' class='print-btn'>Print Receipt</a></td>";

            // New Contact Owner button
            echo "<td><a href='contact_owner.php?pet_id=" . $row['pet_id'] . "&adoption_id=" . $row['adoption_id'] . "' class='contact-btn'>Contact Owner</a></td>";

            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='9'>No adoption applications found.</td></tr>";
    }
    ?>
</tbody>

    </table> 

<!-- Back Button -->
<a href="user.php" class="back-btn">Back to Pet Listings</a>
<?php include 'footer.php'; ?>
</body>
</html>
