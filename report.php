<?php
// Assuming you have an existing database connection 
// in a separate file named 'db_connection.php'

include 'db_connection.php'; 

// SQL query to join tables for the adoption report
$sql = "SELECT 
            a.adoption_id, 
            a.full_name, 
            a.email, 
            a.phone, 
            a.application_status, 
            a.payment_status, 
            p.name AS pet_name, 
            p.species, 
            p.breed,
            u.name 
        FROM 
            adoptions a
        JOIN 
            pets p ON a.pet_id = p.pet_id
        JOIN 
            users u ON a.full_name = u.name"; 

$result = $conn->query($sql);

// SQL queries for counts
$totalAdoptionsQuery = "SELECT COUNT(*) AS total_adoptions FROM adoptions";
$totalPetsQuery = "SELECT COUNT(*) AS total_pets FROM pets";
$totalAvailablePetsQuery = "SELECT COUNT(*) AS available_pets FROM pets WHERE availability = 'available'";
$totalUnavailablePetsQuery = "SELECT COUNT(*) AS unavailable_pets FROM pets WHERE availability = 'unavailable'";
$totalUsersQuery = "SELECT COUNT(*) AS total_users FROM users";

// Execute the count queries
$totalAdoptionsResult = $conn->query($totalAdoptionsQuery);
$totalPetsResult = $conn->query($totalPetsQuery);
$totalAvailablePetsResult = $conn->query($totalAvailablePetsQuery);
$totalUnavailablePetsResult = $conn->query($totalUnavailablePetsQuery);
$totalUsersResult = $conn->query($totalUsersQuery);

// Fetch the count data
$totalAdoptions = $totalAdoptionsResult->fetch_assoc()['total_adoptions'];
$totalPets = $totalPetsResult->fetch_assoc()['total_pets'];
$totalAvailablePets = $totalAvailablePetsResult->fetch_assoc()['available_pets'];
$totalUnavailablePets = $totalUnavailablePetsResult->fetch_assoc()['unavailable_pets'];
$totalUsers = $totalUsersResult->fetch_assoc()['total_users'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adoption Reports</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .count-table {
            margin-top: 20px;
        }

        .chart-container {
            width: 50%;
            margin: auto;
        }
    </style>
</head>
<body>

    <h1>Adoption Reports</h1>

    <!-- Pie Chart Section -->
    <h2>Adoption Statistics</h2>
    <div class="chart-container">
        <canvas id="adoptionChart"></canvas>
    </div>

    <!-- Adoption Details Table -->
    <h2>Adoption Details</h2>
    <?php
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>
                <th>Adoption ID</th>
                <th>Full Name</th>
                <th>Username</th> 
                <th>Email</th>
                <th>Phone</th>
                <th>Application Status</th>
                <th>Payment Status</th>
                <th>Pet Name</th>
                <th>Species</th>
                <th>Breed</th>
            </tr>";

        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["adoption_id"] . "</td>";
            echo "<td>" . $row["full_name"] . "</td>";
            echo "<td>" . $row["name"] . "</td>"; 
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["phone"] . "</td>";
            echo "<td>" . $row["application_status"] . "</td>";
            echo "<td>" . $row["payment_status"] . "</td>";
            echo "<td>" . $row["pet_name"] . "</td>";
            echo "<td>" . $row["species"] . "</td>";
            echo "<td>" . $row["breed"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "0 results";
    }

    $conn->close();
    ?>

    <script>
        // Data for the Pie chart
        const ctx = document.getElementById('adoptionChart').getContext('2d');
        const adoptionChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Total Adoptions', 'Total Pets', 'Available Pets', 'Unavailable Pets', 'Total Users'],
                datasets: [{
                    label: 'Adoption Statistics',
                    data: [
                        <?php echo $totalAdoptions; ?>, 
                        <?php echo $totalPets; ?>, 
                        <?php echo $totalAvailablePets; ?>, 
                        <?php echo $totalUnavailablePets; ?>, 
                        <?php echo $totalUsers; ?>
                    ],
                    backgroundColor: ['#FF5733', '#33FF57', '#3357FF', '#FF33A1', '#F4D03F'],
                    borderColor: ['#FF5733', '#33FF57', '#3357FF', '#FF33A1', '#F4D03F'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    </script>

</body>
</html>
