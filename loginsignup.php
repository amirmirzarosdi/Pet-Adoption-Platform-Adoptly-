<?php
// Start session and include database connection
session_start();
include 'db_connection.php';

// Handle Sign-Up
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sign_up'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone']; // Retrieve phone number
    $role = 'user'; // Set role to user by default

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already exists! Please login.');</script>";
    } else {
        $sql = "INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssss', $name, $email, $password, $phone, $role);
        if ($stmt->execute()) {
            echo "<script>alert('Sign-up successful! You can now log in.');</script>";
        } else {
            echo "<script>alert('Error during sign-up.');</script>";
        }
    }
}



// Handle Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $destination = $_POST['destination'];

    // Check for fixed admin login
    if ($email === 'admin@gmail.com' && $password === '123') {
        // Admin login successful
        $_SESSION['username'] = 'Admin';
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'admin';
        echo "<script>
                alert('Admin login successful!');
                window.location.href = 'adminpage.php'; // Redirect to adminpage.php
              </script>";
    } else {
        // Proceed with normal user login
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['username'] = $user['name'];
            $_SESSION['email'] = $user['email']; // Store email in session
            $_SESSION['phone'] = $user['phone']; // Store phone in session
            $_SESSION['role'] = $user['role']; // Store role in session
            echo "<script>
                    alert('Login successful!');
                    window.location.href = '$destination'; // Redirect to selected destination
                  </script>";
        } else {
            // Login failed
            echo "<script>alert('Invalid email or password.');</script>";
        }
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in || Sign up form</title>
    <!-- font awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- css stylesheet -->
    <link rel="stylesheet" href="loginsignup.css">
    <style>
        /* Style for the select dropdown */
.infield select {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
    font-size: 16px;
    color: #333;
    appearance: none; /* Remove default arrow */
    -webkit-appearance: none; /* Remove default arrow for Safari */
    -moz-appearance: none; /* Remove default arrow for Firefox */
    background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"><path fill="#333" d="M2 0L0 2h4z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 10px top 50%;
    background-size: 10px 10px;
}

/* Style for the options */
.infield select option {
    padding: 10px;
    background-color: #fff;
    color: #333;
}
    </style>
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="" method="POST">
                <h1>Create Account</h1>
                <div class="infield">
                    <input type="text" placeholder="Name" name="name" required />
                    <label></label>
                </div>
                <div class="infield">
                    <input type="email" placeholder="Email" name="email" required />
                    <label></label>
                </div>
                <div class="infield">
                    <input type="text" placeholder="Phone Number" name="phone" required />
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" placeholder="Password" name="password" required />
                    <label></label>
                </div>
                <button type="submit" name="sign_up">Sign Up</button>
                <button type="button" onclick="window.location.href='index.php';">Back</button> <!-- Back Button -->
            </form>
        </div>

        <div class="form-container sign-in-container">
    <form action="" method="POST">
        <h1>Sign in</h1>
        <img src="Pet Adoption.png" alt="Pet Adoption Platform Logo" style="height: 100px;">
        <div class="infield">
            <input type="email" placeholder="Email" name="email" required />
            <label></label>
        </div>
        <div class="infield">
            <input type="password" placeholder="Password" name="password" required />
            <label></label>
        </div>
        <div class="infield">
            <select name="destination" required>
                <option value="" disabled selected>Select Destination</option>
                <option value="user.php">Adoption</option>
                <option value="strayfinder.php">Stray Finder</option>
            </select>
        </div>
        <button type="submit" name="login">Sign In</button>
        <button type="button" onclick="window.location.href='index.php';">Back</button> <!-- Back Button -->
    </form>
</div>


        <div class="overlay-container" id="overlayCon">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1 style="color: white;">Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button>Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1 style="color: white;">Hello, Friend!</h1>
                    <p>Enter your personal details and start your journey with us</p>
                    <button>Sign Up</button>
                </div>
            </div>
            <button id="overlayBtn"></button>
        </div>
    </div>

    <script>
        const container = document.getElementById('container');
        const overlayCon = document.getElementById('overlayCon');
        const overlayBtn = document.getElementById('overlayBtn');

        overlayBtn.addEventListener('click', ()=> {
            container.classList.toggle('right-panel-active');

            overlayBtn.classList.remove('btnScaled');
            window.requestAnimationFrame( ()=> {
                overlayBtn.classList.add('btnScaled');
            })
        });
    </script>
</body>
</html>
