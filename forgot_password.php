<?php

require 'db_connect.php';

session_start();
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM user_details WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Generate a 6-digit code
        $code = rand(100000, 999999);
        $_SESSION['reset_code'] = $code;
        $_SESSION['reset_email'] = $email;

        // Send Email via PHPMailer
        $mail = new PHPMailer(true);
        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Use Outlook/Yahoo SMTP if needed
            $mail->SMTPAuth = true;
            $mail->Username = 'kolekarp04082003@gmail.com'; // Your email
            $mail->Password = 'vbtc tvyc rdpn xnzl'; // Use App Password if needed
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Email Content
            $mail->setFrom('kolekarp04082003@gmail.com', 'Image Gallery');
            $mail->addAddress($email);
            $mail->Subject = "Password Reset Code";
            $mail->Body = "Your password reset code is: $code";

            $mail->send();
            header("Location: verify_code.php");
            exit();
        } catch (Exception $e) {
            echo "<p style='color:red;'>Email could not be sent. Error: {$mail->ErrorInfo}</p>";
        }
    } else {
        echo "<p style='color:red;'>Email not found!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="forgot_password.php" method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit">Send Code</button>
    </form>
</body>
</html>

