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
        <h1>Terms & Condition</h1>
    </div>

    <!-- Content Section -->
    <div class="content">
        <h2>Terms and Conditions</h2>
        <p>Welcome to the Pet Adoption Platform. By accessing or using our website, you agree to comply with and be bound by the following terms and conditions:</p>
        
        <h3>1. Acceptance of Terms</h3>
        <p>By accessing this website, you agree to be bound by these Terms and Conditions and our Privacy Policy. If you do not agree with any part of these terms, you must not use our website.</p>
        
        <h3>2. Use of the Website</h3>
        <p>You agree to use the website only for lawful purposes and in a way that does not infringe the rights of, restrict, or inhibit anyone else's use and enjoyment of the website.</p>
        
        <h3>3. User Responsibilities</h3>
        <ul>
            <li>You are responsible for maintaining the confidentiality of your account information.</li>
            <li>You must not misuse our website by knowingly introducing viruses or other material that is malicious or technologically harmful.</li>
        </ul>
        
        <h3>4. Limitation of Liability</h3>
        <p>We will not be liable for any loss or damage arising from your use of our website, including but not limited to any direct, indirect, incidental, punitive, or consequential damages.</p>
        
        <h3>5. Privacy Policy</h3>
        <p>Your privacy is important to us. Please review our Privacy Policy, which also governs your visit to our website, to understand our practices.</p>
        
        <h3>6. Changes to Terms</h3>
        <p>We reserve the right to modify these terms at any time. Your continued use of the website following any changes signifies your acceptance of the new terms.</p>
        
        <h3>7. Contact Information</h3>
        <p>If you have any questions about these Terms and Conditions, please contact us at [Your Contact Information].</p>
    </div>


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
