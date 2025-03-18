<?php
session_start();
include 'db_connect.php'; // Include database connection
require 'mail_connect.php'; // Include mail connection


$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Password validation (at least 8 characters, one uppercase, one lowercase, one number, one special char)
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $error = "❌ Password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a number, and a special character!";
    } elseif ($password !== $confirm_password) {
        $error = "❌ Passwords do not match!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if an unverified account exists for this email
        $stmt = $conn->prepare("DELETE FROM user_details WHERE email = ? AND verified = 0");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        // Check if username already exists
        $stmt = $conn->prepare("SELECT username FROM user_details WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "❌ Username already exists!";
        } else {
            // Generate a 6-digit verification code
            $verification_code = rand(100000, 999999);
            $_SESSION['verification_code'] = $verification_code;
            $_SESSION['email'] = $email;

            // Insert the new user as unverified (verified = 0)
            $stmt = $conn->prepare("INSERT INTO user_details (username, email, password, verified) VALUES (?, ?, ?, 0)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                // Send Email via PHPMailer
                try {

                    $mail->setFrom('your-email@gmail.com', 'Image Gallery');
                    $mail->addAddress($email);
                    $mail->Subject = "Verify Your Email";
                    $mail->Body = "Your verification code is: $verification_code";

                    $mail->send();
                    header("Location: verify_signup.php");
                    exit();
                } catch (Exception $e) {
                    $error = "❌ Email could not be sent!";
                }
            } else {
                $error = "❌ Error creating account!";
            }
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup | Image Gallery</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="assets/css/form_&_bg.css">

</head>
<body>

    <div class="login-container">
        <h2>Signup</h2>
        <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>
        <?php if ($success) { echo "<p class='success'>$success</p>"; } ?>

        <form method="post">
            <div class="mb-3"><input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required></div>
            <div class="mb-3"><input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required></div>
            <div class="mb-3"><input type="password" class="form-control" name="password" placeholder="Password" required></div>
            <div class="mb-3"><input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required></div>
            <button type="submit" class="btn btn-primary" name="signup">Signup</button>
        </form>

        <div class="text-center mt-3">
            <p>Already have an account? <a href="login.php" class="btn btn-link">Login</a></p>
        </div>
    </div>

</body>
</html>
