<?php
session_start();
include '../database/db_connect.php';

// Redirect if reset email is not set
if (!isset($_SESSION["reset_email"])) {
    header("Location: resetpassword.php");
    exit();
}

$email = $_SESSION["reset_email"];
$message = "";
$toastClass = "";

// Handle password reset
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        $message = "❌ Passwords do not match!";
        $toastClass = "bg-warning";
    } else {
        // ✅ Update password in database
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        if (!$stmt) {
            die("SQL Error: " . $conn->error); // Debugging SQL error
        }

        $stmt->bind_param("ss", $new_password, $email);

        if ($stmt->execute()) {
            $message = "✅ Password updated successfully!";
            $toastClass = "bg-success";
            session_destroy(); // Destroy session after reset
            header("refresh:3; url=login.php"); // Redirect to login after 3 seconds
        } else {
            $message = "❌ Error updating password!";
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
    <title>Set New Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Full height to center vertically */
        .full-height {
            height: 100vh;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <?php if (!empty($message)): ?>
            <div class="alert <?php echo $toastClass; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="post" class="card p-4">
            <h3 class="text-center">Set New Password</h3>
            <div class="mb-3">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Update Password</button>
        </form>
    </div>
</body>
</html>
