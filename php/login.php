<?php
include '../database/db_connect.php';
session_start();

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Retrieve user data
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Ensure user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $stored_password, $role);
        $stmt->fetch();

        // Verify password (remove `password_verify` if passwords are stored in plain text)
        if ($password === $stored_password) {
            $_SESSION["user_email"] = $email;
            $_SESSION["user_id"] = $user_id;
            $_SESSION["user_role"] = $role; // Store role in session

            // Redirect based on role
            if ($role === "admin") {
                header("Location: ../html/admin_dashboard.php");
            } else {
                header("Location: ../php/profile.php");
            }
            exit();
        } else {
            $message = "Error: Invalid email or password!";
            $toastClass = "bg-danger";
        }
    } else {
        $message = "Error: No account found with this email!";
        $toastClass = "bg-warning";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Full height to center vertically */
        .full-height {
            height: 85vh;
        }
        body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      font-family: Arial, sans-serif;
    }
    
    /* Style for the main content area */
    .main-content {
      flex-grow: 1;
    }
    
    .hero-section {
      background: linear-gradient(to right, #4A90E2, #50B3A2);
      color: white;
      text-align: center;
      padding: 100px 0;
    }
    .services-section, 
    .phases-section, 
    .about-section, 
    .reviews-section, 
    .footer {
      padding: 60px 20px;
    }
    .footer {
      background: #222;
      color: white;
      text-align: center;
      padding: 15px 0;
      width: 100%;
      margin-top: auto; /* Pushes footer to the bottom */
    }
    </style>
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
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="./html/pricing.html">Pricing</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>

                    <li class="nav-item"><a class="nav-link" href="./login.php">Login/Signup</a></li>
                
            </ul>
        </div>
    </div>
</nav>
    <div class="container full-height d-flex justify-content-center align-items-center">
        <div class="col-md-6 col-lg-5 col-xl-4">
            <?php if (!empty($message)): ?>
                <div class="alert <?php echo $toastClass; ?> text-white text-center"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="post" class="card p-4 shadow-lg">
                <h3 class="text-center mb-3">Login</h3>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Login</button>
                <p class="text-center mt-3">
                    <a href="resetpassword.php">Forgot Password?</a> <br>
                    <a href="register.php">Don't have an account?</a>
                </p>
            </form>
        </div>
    </div>

        <!-- Footer -->
        <footer class="footer">
        <p>&copy; 2025 MarketPro. All Rights Reserved.</p>
    </footer>

</body>
</html>

