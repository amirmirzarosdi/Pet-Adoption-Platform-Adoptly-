<?php
// Include database connection
include 'db_connection.php';

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

            .pet-care-resources {
    padding: 50px 20px;
    background-color: #ffffff;
    text-align: center;
}

.pet-care-resources h2 {
    font-size: 36px;
    margin-bottom: 30px;
    color: #333;
}

.resources-list {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.resource-item {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 30%;
    max-width: 300px;
    text-align: left;
}

.resource-item h3 {
    font-size: 24px;
    color: #3498db;
}

.resource-item p {
    font-size: 16px;
    color: #555;
}

.resource-item a {
    display: inline-block;
    margin-top: 10px;
    color: #3498db;
    text-decoration: none;
    font-weight: bold;
}

.resource-item a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .resources-list {
        flex-direction: column;
        align-items: center;
    }

    .resource-item {
        width: 90%;
    }
}

.site-footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 20px 0;
    position: relative;
    bottom: 0;
    width: 100%;
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 10px 0 0;
    display: flex;
    justify-content: center;
    gap: 15px;
}

.footer-links li {
    display: inline;
}

.footer-links a {
    color: white;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-links a:hover {
    color: #ddd;
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
        </script>
    </head>
    <body>
    <nav class="mask">
    <a href="index.php">
        <img src="Pet Adoption.png" alt="Pet Adoption Platform Logo" style="height: 60px;">
    </a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <a href="#"></a>
            <ul class="list">
                <li><a href="aboutus.php">About Us</a></li>
                <a href="#"></a>
                <li><a href="maincontactus.php">Contact</a></li>
                <a href="#"></a>
                <li><a href="petcare.php">Pet Care Resources</a></li>
                <a href="#"></a>
                <li><a href="termsandcondition.php">Terms & Conditions</a></li>
                <a href="#"></a>
            </ul>
        </nav>
            
        <!-- Hero Section -->
        <div class="image-container">
            <img src="petcare.png" alt="Welcoming a Pet into Your Home">
            <div class="text">
                <h1>Pet Care Resouces</h1>
            </div>
        </div>

        <section class="pet-care-resources">
    <h2>Pet Care Resources</h2>
    <div class="resources-list">
        <?php while ($resource = mysqli_fetch_assoc($result)): ?>
            <div class="resource-item">
                <img src="<?php echo htmlspecialchars($resource['image_path']); ?>" alt="<?php echo htmlspecialchars($resource['title']); ?>" style="width:100%; height:auto; border-radius:10px;">
                <h3><?php echo htmlspecialchars($resource['title']); ?></h3>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($resource['type']); ?></p>
                <p><?php echo htmlspecialchars($resource['description']); ?></p>
                <a href="<?php echo htmlspecialchars($resource['link']); ?>" target="_blank">Got To Page</a>
            </div>
        <?php endwhile; ?>
    </div>
</section>



        <!-- Back to Top Button -->
        <div class="back-to-top" onclick="scrollToTop()">&#8679;</div>
    </body>

    <footer class="site-footer">
    <div class="footer-content">
        <p>&copy; 2023 Pet Adoption Platform. All rights reserved.</p>
        <ul class="footer-links">
            <li><a href="aboutus.php">About Us</a></li>
            <li><a href="maincontactus.php">Contact Us</a></li>
            <li><a href="termsandcondition.php">Terms & Conditions</a></li>
        </ul>
    </div>
</footer>
</html>
