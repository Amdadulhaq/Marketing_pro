<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$tracking_id = "G-NG72B0DHZD"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Analytics Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $tracking_id; ?>"></script>
    <link rel="stylesheet" href="../css/user_dashboard.css">
    <script>
        // Google Analytics Script
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo $tracking_id; ?>');
    </script>
</head>
<body class="bg-light">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">MarketPro</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link active" href="business_analysis.php">Business Analysis</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container mt-5">
        <h2 class="text-center mb-4">Business Analytics Dashboard</h2>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-lg p-4">
                    <h5 class="text-center">Website Traffic</h5>
                    <p>View real-time and historical traffic trends on your website.</p>
                    <a href="https://analytics.google.com/" target="_blank" class="btn btn-primary w-100">View in Google Analytics</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-lg p-4">
                    <h5 class="text-center">User Engagement</h5>
                    <p>Track active users, session durations, and bounce rates.</p>
                    <a href="https://analytics.google.com/" target="_blank" class="btn btn-secondary w-100">Check Engagement</a>
                </div>
            </div>
        </div>

        <div class="container mt-4">
    <div class="text-center">
        <img src="../images/analytics.jpg" class="img-fluid rounded shadow-lg" alt="Business Analytics Dashboard">
    </div>
        </div>

    </div>

    <footer class="footer bg-dark text-white text-center mt-5 p-3">
        &copy; 2025 MarketPro. All Rights Reserved.
    </footer>
</body>
</html>
