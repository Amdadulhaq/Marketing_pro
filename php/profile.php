<?php
session_start();
include '../database/db_connect.php';

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";
$toastClass = "";

// Fetch user data
$stmt = $conn->prepare("SELECT username, email, phone, address, pin_code, website FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($username, $email, $phone, $address, $pin_code, $website);
$stmt->fetch();
$stmt->close();


// Handle profile update (Including PIN)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = isset($_POST['username']) ? $_POST['username'] : '';
    $newEmail = isset($_POST['email']) ? $_POST['email'] : '';
    $newPhone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $newAddress = isset($_POST['address']) ? $_POST['address'] : '';
    $newPin = isset($_POST['pin']) ? $_POST['pin'] : '';
    $newWebsite = isset($_POST['website']) ? $_POST['website'] : '';

    // Ensure PIN is exactly 4 digits
    if (!empty($newPin) && !preg_match("/^\d{4}$/", $newPin)) {
        $message = "PIN must be exactly 4 digits!";
        $toastClass = "alert-danger";
    } else {
        $updateStmt = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ?, address = ?, pin_code = ?, website = ? WHERE id = ?");
        $updateStmt->bind_param("ssssssi", $newUsername, $newEmail, $newPhone, $newAddress, $newPin, $newWebsite, $user_id);

        if ($updateStmt->execute()) {
            $message = "✅ Profile updated successfully!";
            $toastClass = "alert-success";
            $_SESSION['user_email'] = $newEmail; // Update session email
        } else {
            $message = "❌ Error updating profile!";
            $toastClass = "alert-danger";
        }

        $updateStmt->close();
        header("Refresh:2"); // Reload the page after 2 seconds
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/user_dashboard.css">
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
                    <li class="nav-item"><a class="nav-link active" href="dashboard.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link active" href="index.php">Website Demo</a></li>
                    <li class="nav-item"><a class="nav-link active" href="business_analysis.php">Business Analysis</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Profile Container -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-secondary text-white text-center">
                        <h3>Business Profile</h3>
                    </div>
                    <div class="card-body">
                        <!-- Display message -->
                        <?php if (!empty($message)): ?>
                            <div class="alert <?php echo $toastClass; ?> text-center"><?php echo $message; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Business Name</label>
                                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($phone); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control"><?php echo htmlspecialchars($address); ?></textarea>
                            </div>
                            <div class="mb-3">
                            <label class="form-label">Business Website</label>
                            <input type="text" name="website" class="form-control" value="<?php echo htmlspecialchars($website); ?>" required>
                           
                            </div>

                            <div class="mb-3">
                                <label class="form-label">4-Digit PIN (For Password Reset)</label>
                                <input type="password" name="pin" class="form-control" value="<?php echo htmlspecialchars($pin_code); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer text-center mt-4">
        <p>&copy; 2025 MarketPro. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
