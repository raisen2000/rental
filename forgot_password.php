<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include 'db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'assets/vendor/phpmailer/phpmailer/src/Exception.php';
require 'assets/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'assets/vendor/phpmailer/phpmailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
}

$email = "lance.musngi@gmail.com";

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo '<script>alert("Invalid email format. Please try again.");window.location.href = "forgot_password.php";</script>';
    exit();
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
            $mail->Body = "Dear user,<br><br>Your OTP is: <strong>$temporaryOTP</strong><br>Please use this password to log in and reset it immediately.<br><br>Best regards,<br>Your Organization";

            $mail->send();
            echo "<script>alert('A OTP has been sent to your email address.');</script>";
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

<div class="form-container">
    <h3>Forgot Password</h3>
    <form method="POST" action="forgot_password.php">
        <input type="otp" name="otp" placeholder="Enter OTP" required>
        <button type="submit">COnfirm</button>
    </form>
</div>

</html>