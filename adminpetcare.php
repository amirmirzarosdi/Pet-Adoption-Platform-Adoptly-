<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $link = $_POST['link'];
    $type = $_POST['type'];

    // Handle image upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 5MB)
    if ($_FILES["image"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Insert the new resource into the database
            $stmt = $conn->prepare("INSERT INTO pet_care_resources (title, description, link, image_path, type) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $title, $description, $link, $target_file, $type);
            $stmt->execute();
            $stmt->close();

            // Redirect to the same page to clear the form
            header("Location: adminpetcare.php");
            exit();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Fetch the username from the session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";

// Fetch pet care resources from the database
$query = "SELECT * FROM pet_care_resources";
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

        .form-container, .table-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h2, .table-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-container input,
        .form-container select,
        .form-container textarea,
        .form-container button {
            width: 98%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-container button {
            background-color: #000000;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #333;
        }

        .resource-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .resource-table th, .resource-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .resource-table th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .edit-btn, .delete-btn {
            padding: 5px 10px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .edit-btn {
            background-color: #4CAF50;
        }

        .edit-btn:hover {
            background-color: #45a049;
        }

        .delete-btn {
            background-color: #f44336;
        }

        .delete-btn:hover {
            background-color: #da190b;
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <img style="opacity: 70%;" src="petcare.png" alt="Background Image">
    <div class="text">
        <h1 style="size: 100%">ADD PET CARE RESOURCES</h1>
    </div>
</div>

<div class="form-container">
    <h2>Add Pet Care Resource</h2>
    <form method="POST" action="adminpetcare.php" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Resource Title" required>
        <textarea name="description" placeholder="Resource Description" required></textarea>
        <input type="url" name="link" placeholder="Resource Link" required>
        <input type="file" name="image" accept="image/*" required>
        <select name="type" required>
            <option value="Clinical">Clinical</option>
            <option value="Accessories">Accessories</option>
            <option value="Others">Others</option>
        </select>
        <button type="submit">Add Resource</button>
    </form>
</div>

<div class="table-container">
    <h2>Uploaded Pet Care Resources</h2>
    <table class="resource-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Link</th>
                <th>Image</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td><a href='" . htmlspecialchars($row['link']) . "' target='_blank'>View</a></td>";
                    echo "<td><img src='" . htmlspecialchars($row['image_path']) . "' alt='Resource Image' style='max-width: 100px;'></td>";
                    echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                    echo "<td>";
                    echo "<button class='edit-btn' onclick='window.location.href=\"edit_petcare.php?id=" . $row['petcare_id'] . "\"'>Edit</button> ";
                    echo "<button class='delete-btn' onclick='window.location.href=\"delete_petcare.php?id=" . $row['petcare_id'] . "\"'>Delete</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No resources found.</td></tr>";
            }
            ?>
        </tbody>

    </table>
</div>

<!-- Back to Top Button -->
<div class="back-to-top" onclick="scrollToTop()">&#8679;</div>
<?php include 'footer.php'; ?>
</body>
</html>
