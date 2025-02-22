<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


include 'php/chatbot.php'; 

$loggedIn = isset($_SESSION['user_email']);
$userRole = $_SESSION['user_role'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarketPro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">MarketPro</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="./html/pricing.html">Pricing</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>

                    <li class="nav-item"><a class="nav-link" href="./php/login.php">Login/Signup</a></li>
                
            </ul>
        </div>
    </div>
</nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <h1>Welcome To MarketPro</h1>
        <p>Your one-stop solution for professional website design, development, and maintenance services for your Business</p>
    </section>

    <!-- Services Section -->
    <section class="services-section">
        <div class="container">
            <h3>Our Services</h3>
            <div class="row">
                <div class="col-md-4">
                    <h5>Website Creation</h5>
                    <p>We build stunning and responsive websites tailored to your needs.</p>
                </div>
                <div class="col-md-4">
                    <h5>Website Maintenance</h5>
                    <div class="card" style="width: 18rem;">
                        <img src="./images/maint.png" class="card-img-top img-fluid" alt="img">
                        <div class="card-body">
                          <p class="card-text">Ensure your website stays secure and up-to-date with our maintenance services</p>
                        </div>
                      </div>
                </div>
                <div class="col-md-4">
                    <h5>SEO Optimization</h5>
                    <p>Boost your online presence and get noticed with our SEO services.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section class="about-section">
        <div class="container">
            <h3>About Us</h3>
            <div class="row">
                <div class="col-md-4 column bg-light">
                    <h5>Who We Are</h5>
                    <p>We are a team of experts dedicated to helping businesses grow online with tailored digital solutions.</p>
                </div>
                <div class="col-md-4 column bg-light">
                    <h5>Why Choose Us</h5>
                    <p>We prioritize your goals, ensuring quality and timely delivery with exceptional support.</p>
                </div>
                <div class="col-md-4 column bg-light">
                    <h5>Vision & Mission</h5>
                    <p>Our mission is to empower businesses with digital tools and make their online presence impactful.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 MarketPro. All Rights Reserved.</p>
    </footer>

    <!-- User Authentication Script (Session-Based) -->
    <script>
        function checkUserStatus() {
            fetch('./php/check_session.php')
                .then(response => response.json())
                .then(data => {
                    if (data.loggedIn) {
                        document.getElementById("loginNav").classList.add("d-none");
                        document.getElementById("logoutNav").classList.remove("d-none");

                        if (data.role === "admin") {
                            document.getElementById("adminDashboardNav").classList.remove("d-none");
                        } else {
                            document.getElementById("dashboardNav").classList.remove("d-none");
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        window.onload = checkUserStatus;

        
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
