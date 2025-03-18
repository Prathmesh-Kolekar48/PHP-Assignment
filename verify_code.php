<?php
session_start();
require 'db_connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_code = trim($_POST['code']);
    $email = $_SESSION['reset_email'] ?? '';

    // Input validation: Check if entered code is a valid 6-digit number
    if (!preg_match('/^\d{6}$/', $entered_code)) {
        $error = "❌ Invalid code format. Please enter a 6-digit number.";
    } else {
        // Check if the email exists in session
        if (empty($email)) {
            $error = "❌ Session expired. Please request a new reset code.";
        } else {
            // Fetch the reset code from the database
            $stmt = $conn->prepare("SELECT reset_code FROM password_resets WHERE email = ? AND expiry_time > NOW()");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row && $entered_code == $row['reset_code']) {
                // Code is correct, redirect to reset password page
                $_SESSION['verified'] = true; // Set flag to allow password reset
                header("Location: reset_password.php");
                exit();
            } else {
                $error = "❌ Invalid or expired code. Try again!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code | Image Gallery</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="assets/css/form_&_bg.css">
</head>
<body>

    <div class="container">
        <h2>Verify Code</h2>

        <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>

        <form action="verify_code.php" method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="code" placeholder="Enter the code" required>
            </div>
            <button type="submit" class="btn btn-primary">Verify</button>
        </form>

        <div class="text-center mt-3">
            <a href="forgot_password.php" class="btn btn-link">Didn't receive a code? Resend</a>
        </div>
    </div>

</body>
</html>
