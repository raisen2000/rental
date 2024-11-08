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

// Generate and store OTP
function generateAndStoreOTP($conn, $username)
{
    $otp = rand(100000, 999999);

    // Store OTP in the database associated with the user
    $sql = "UPDATE users SET otp = ? WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "is", $otp, $username);

    if (mysqli_stmt_execute($stmt)) {
        return $otp;
    } else {
        // Handle database error
        echo "Error storing OTP: " . mysqli_error($conn);
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username']; // Get username from form

    // Generate and store OTP
    $otp = generateAndStoreOTP($conn, $username);

    if ($otp) {
        // Get user's email
        $sql = "SELECT email FROM `users` WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $userEmail = $row['email'];

            // Send email using PHPMailer
            $mail = new PHPMailer(true);
            try {
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
                $mail->send();
                // Redirect to OTP verification page
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