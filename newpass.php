<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $otp = $_GET['otp'];

    $checkotp = $conn->prepare("SELECT * from users where otp = ?");
    $checkotp ->bind_param("s", $otp);

    if ($checkotp ->execute()) {
        echo "<script>alert('Correcty.');</script>";
    } else {
        echo "<script>alert('Wrong otp.');window.history.back();</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if passwords match
    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.');window.location.href = 'new_password.php';</script>";
        exit();
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Assuming user's email is stored in session after OTP verification
    session_start();
    $email = $_SESSION['email'];

    if ($email) {
        // Update the user's password
        $updateStmt = $conn->prepare("UPDATE users SET password = ?, otp = NULL, token_expiry = NULL WHERE email = ?");
        $updateStmt->bind_param("ss", $hashedPassword, $email);

        if ($updateStmt->execute()) {
            echo "<script>alert('Password updated successfully.');window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('Error updating password. Please try again.');window.location.href = 'new_password.php';</script>";
        }

        $updateStmt->close();
    } else {
        echo "<script>alert('Session expired. Please restart the process.');window.location.href = 'forgot_password.php';</script>";
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: url('bg.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            position: relative;
        }

        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: inherit;
            filter: blur(8px);
            z-index: 1;
        }

        .card-container {
            position: relative;
            z-index: 2;
        }

        .card {
            background: rgba(255, 255, 255, 0.85);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
        }

        h3 {
            color: #333;
        }
    </style>
</head>

<body>

    <div class="background-overlay"></div>

    <div class="container d-flex justify-content-center align-items-center card-container">
        <div class="card shadow">
            <h3 class="text-center mb-4">Create New Password</h3>
            <form method="POST" action="new_password.php">
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter New Password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>