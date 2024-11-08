<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

$otp = rand(100000, 999999);

session_start();
$_SESSION['otp'] = $otp;

// Get user's email (you might need to adjust this based on your form)
$userEmail = $_POST['email']; // Assuming you have an email input field in your form

// Send email using PHPMailer
$mail = new PHPMailer(true);

try {
    // Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'your_smtp_host';                     // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username = 'no-reply@ohrmslpa.site';
    $mail->Password = 'Ohrmslpa@2024';                             // SMTP password
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;                                // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    // Recipients
    $mail->setFrom('no-reply@ohrmslpa.site', 'Ohrmslpa.site');
    $mail->addAddress($userEmail, 'User Name');     // Add a recipient

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Password Reset OTP';
    $mail->Body    = "Your OTP for password reset is: <b>$otp</b>";
    $mail->AltBody = "Your OTP for password reset is: $otp";

    $mail->send();
    echo 'OTP has been sent to your email!';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
