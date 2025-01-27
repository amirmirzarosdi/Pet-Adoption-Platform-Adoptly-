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
            gap: 20px;
        }

        .list li a {
            text-decoration: none;
            color: white;
        }

        .hero {
            background: url('homebg.png') no-repeat center center/cover;
            height: 60vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .hero h1 {
            font-size: 48px;
            margin: 0;
        }

        .content {
            padding: 60px 20px;
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }

        .content h2 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #333;
        }

        .content p {
            font-size: 18px;
            line-height: 1.6;
            color: #555;
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
            .back-to-top:hover {
                background-color: #333;
            }

            @media (max-width: 768px) {
            .hero h1 {
                font-size: 32px;
            }

            .content h2 {
                font-size: 28px;
            }

            .content p {
                font-size: 16px;
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
    <div class="hero">
        <h1>Welcome to Adoptly</h1>
    </div>

    <!-- Content Section -->
    <div class="content">
        <h2>About Us</h2>
        <img src="Pet Adoption.png" alt="Pet Adoption Platform Logo" style="height: 200px;">
        <p>We are dedicated to helping you find the perfect pet for your family. 
            Our platform connects you with shelters and adoption centers to make 
            the process seamless and joyful.</p>
</div>
            <!-- How We Help Section -->
        <section class="how-we-help">
            <h2>How We Help</h2>
            <div class="help-items">
                <div class="help-item">
                    <img src="4.png" alt="Adoption Process">
                    <p>Fostering and Adoption</p>
                </div>
                <div class="help-item">
                    <img src="5.png" alt="Access Pet Care Resources">
                    <p>Access Petcare Resources</p>
                </div>
                <div class="help-item">
                    <img src="6.png" alt="Stray Animal Finder">
                    <p>Stray Finder</p>
                </div>
            </div>
        </section>
        <footer class="site-footer">
    <div class="footer-content">
        <p>&copy; 2025 Pet Adoption Platform. All rights reserved.</p>
        <ul class="footer-links">
            <li><a href="aboutus.php">About Us</a></li>
            <li><a href="maincontactus.php">Contact Us</a></li>
            <li><a href="termsandcondition.php">Terms & Conditions</a></li>
        </ul>
    </div>
</footer>
        <!-- Back to Top Button -->
        <div class="back-to-top" onclick="scrollToTop()">&#8679;</div>
    </body>

    
</html>
