<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'assets/vendor/phpmailer/phpmailer/src/Exception.php';
require 'assets/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'assets/vendor/phpmailer/phpmailer/src/SMTP.php';


$username = 'admin';
$sql = "SELECT email FROM `users` WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $email = $row['email'];
}

if (!empty($email)) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $temporaryOTP = bin2hex(random_bytes(4));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $updateStmt = $conn->prepare("UPDATE users SET otp = ?, token_expiry = ? WHERE email = ?");
        $updateStmt->bind_param("sss", $temporaryOTP, $expiry, $email);

        if (!$updateStmt->execute()) {
            echo "<script>alert('Error updating your account. Please try again.');window.location.href = 'forgot_password.php';</script>";
            exit();
        }

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.hostinger.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'no-reply@ohrmslpa.site';
            $mail->Password = 'Ohrmslpa@2024';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('no-reply@ohrmslpa.site', 'Your Organization');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Your OTP';
            $mail->Body = "Dear user,<br><br>Your OTP is: <strong>$temporaryOTP</strong><br>Please use this OTP to log in and reset it immediately.<br><br>Best regards,<br>Your Organization";

            $mail->send();
            echo "<script>alert('An OTP has been sent to your email address.');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Email could not be sent. Error: {$mail->ErrorInfo}');window.location.href = 'forgot_password.php';</script>";
        }
    } else {
        echo '<script>alert("Email not found. Please try again.");window.location.href = "forgot_password.php";</script>';
    }

    $stmt->close();
    $updateStmt->close();
} else {
    echo '<script>alert("Please enter your email address.");window.location.href = "forgot_password.php";</script>';
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom background styling */
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

        /* Blurred overlay */
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

        /* Content card */
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

    <!-- Blurred background overlay -->
    <div class="background-overlay"></div>

    <!-- Content container -->
    <div class="container d-flex justify-content-center align-items-center card-container">
        <div class="card shadow">
            <h3 class="text-center mb-4">Enter OTP</h3>
            <form method="POST" action="forgot_password.php">
                <div class="form-group">
                    <label for="otp">OTP</label>
                    <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter OTP" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Confirm</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>