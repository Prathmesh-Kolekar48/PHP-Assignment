<?php
session_start();
require 'db_connect.php';
require 'mail_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));

    if ($email == '') {
        $_SESSION['error'] = "❌ Email is required!";
        header("Location: forgot_password.php");
        exit();
    }


    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM user_details WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a 6-digit reset code
        $code = rand(100000, 999999);
        $_SESSION['reset_email'] = $email;

        // Set code expiry (e.g., valid for 15 minutes)
        $expiry_time = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // Insert reset code into database
        $stmt = $conn->prepare("INSERT INTO password_resets (email, reset_code, expiry_time) VALUES (?, ?, ?) 
                                ON DUPLICATE KEY UPDATE reset_code = VALUES(reset_code), expiry_time = VALUES(expiry_time)");
        $stmt->bind_param("sss", $email, $code, $expiry_time);
        $stmt->execute();

        // Send Email via PHPMailer
        try {
            $mail->setFrom('kolekarp04082003@gmail.com', 'Image Gallery');
            $mail->addAddress($email);
            $mail->Subject = "Password Reset Code";
            $mail->Body = "Your password reset code is: $code\nThis code is valid for 15 minutes.";

            $mail->send();
            $_SESSION['success'] = "✅ A reset code has been sent to your email.";
            header("Location: verify_code.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = "❌ Email could not be sent. Error: {$mail->ErrorInfo}";
            header("Location: forgot_password.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "❌ Email not found!";
        header("Location: forgot_password.php");
        exit();
    }
}
?>
