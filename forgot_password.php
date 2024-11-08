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

function generateAndStoreOTP($conn, $username)
{
    $otp = rand(100000, 999999);

    $sql = "UPDATE users SET otp = ? WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "is", $otp, $username);
    if (mysqli_stmt_execute($stmt)) {
        return $otp;
    } else {
        echo "Error storing OTP: " . mysqli_error($conn);
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];

    $otp = generateAndStoreOTP($conn, $username);
    if ($otp) {
        $sql = "SELECT email FROM `users` WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $userEmail = $row['email'];

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'your_smtp_host'; 
                $mail->SMTPAuth   = true;
                $mail->Username = 'no-reply@ohrmslpa.site';
                $mail->Password = 'Ohrmslpa@2024';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                $mail->setFrom('no-reply@ohrmslpa.site', 'Ohrmslpa.site');
                $mail->addAddress($userEmail, 'User Name');

                $mail->isHTML(true);
                $mail->Subject = 'Password Reset OTP';
                $mail->Body    = "Your OTP for password reset is: <b>$otp</b>";
                $mail->AltBody = "Your OTP for password reset is: $otp";

                $mail->send(); // Removed duplicate $mail->send()

                header("Location: verify_otp.php?username=" . $username);
                exit();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "User not found.";
        }
    }
}
?>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>
    <input type="submit" value="Send OTP">
</form>