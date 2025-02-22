<?php
session_start(); // 🔹 Start session to store email for the next step
include '../database/db_connect.php';

$message = "";
$toastClass = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["verify_pin"])) {
    $email = $_POST["email"];
    $pin_code = $_POST["pin_code"];

    // Debugging: Check if the email and pin are received
    if (empty($email) || empty($pin_code)) {
        $message = "❌ Please enter both email and PIN!";
        $toastClass = "bg-warning";
    } else {
        // ✅ Check if PIN matches in database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND pin_code = ?");
        if (!$stmt) {
            die("SQL Error: " . $conn->error); // Debugging SQL error
        }

        $stmt->bind_param("ss", $email, $pin_code);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION["reset_email"] = $email; // Store email in session for the next page
            header("Location: new_password.php"); // Redirect to new password page
            exit();
        } else {
            $message = "❌ Invalid PIN or email!";
            $toastClass = "bg-danger";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Full height to center vertically */
        .full-height {
            height: 100vh;
        }
    </style>
        <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-light">
    <div class="container full-height d-flex justify-content-center align-items-center">
        <div class="col-md-6 col-lg-5 col-xl-4">
            <!-- Alert Message (if exists) -->
            <?php if (!empty($message)): ?>
                <div class="alert <?php echo $toastClass; ?> alert-dismissible fade show text-center" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Reset Password Form -->
            <form method="post" class="card p-4 shadow-lg">
                <h3 class="text-center mb-3">Reset Password</h3>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Enter 4-Digit PIN</label>
                    <input type="password" name="pin_code" class="form-control" required pattern="\d{4}" maxlength="4">
                </div>
                <button type="submit" name="verify_pin" class="btn btn-dark w-100">Verify PIN</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
