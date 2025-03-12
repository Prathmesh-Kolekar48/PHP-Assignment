<?php
require 'db_connect.php';
require 'mail_connect.php';
session_start();

$error = "";
$success = "";

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
        try {
            // Email Content
            $mail->setFrom('kolekarp04082003@gmail.com', 'Image Gallery');
            $mail->addAddress($email);
            $mail->Subject = "Password Reset Code";
            $mail->Body = "Your password reset code is: $code";

            $mail->send();
            $success = "✅ A reset code has been sent to your email.";
            header("Location: verify_code.php");
            exit();
        } catch (Exception $e) {
            $error = "❌ Email could not be sent. Error: {$mail->ErrorInfo}";
        }
    } else {
        $error = "❌ Email not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Image Gallery</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .forgot-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 350px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: #333;
        }

        .form-control {
            border-radius: 6px;
            padding: 12px;
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            font-size: 16px;
            background-color: #667eea;
            border: none;
        }

        .btn-primary:hover {
            background-color: #764ba2;
        }

        .error {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-bottom: 15px;
        }

        .success {
            color: green;
            font-size: 14px;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="forgot-container">
        <h2>Forgot Password</h2>
        <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>
        <?php if ($success) { echo "<p class='success'>$success</p>"; } ?>

        <form action="forgot_password.php" method="POST">
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
            </div>
            <button type="submit" class="btn btn-primary">Send Code</button>
        </form>

        <div class="text-center mt-3">
            <a href="login.php" class="btn btn-link">Back to Login</a>
        </div>
    </div>

</body>
</html>
