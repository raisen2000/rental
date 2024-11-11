<?php
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
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('no-reply@ohrmslpa.site', 'Ohrmslpa Reset your Password');
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
