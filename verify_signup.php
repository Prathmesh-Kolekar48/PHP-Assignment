<?php
session_start();
include 'db_connect.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_code = htmlspecialchars(trim($_POST['code']));

    $email = $_SESSION['email'];

    if ($entered_code == "") {
        $error = "❌ Code is required!";
        exit();
    }

    if ($entered_code == $_SESSION['verification_code']) {
        $stmt = $conn->prepare("UPDATE user_details SET verified = 1 WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $success = "✅ Email Verified! <a href='login.php'>Login here</a>";
        session_destroy();
    } else {
        $error = "❌ Invalid code. Try again!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | Image Gallery</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="assets/css/form_&_bg.css">

</head>
<body>

    <div class="container">
        <h2>Verify Your Email</h2>
        <p>We've sent a 6-digit code to your email. Enter it below:</p>

        <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>
        <?php if ($success) { echo "<p class='success'>$success</p>"; } ?>

        <form method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="code" required maxlength="6">
            </div>
            <button type="submit" class="btn btn-primary">Verify</button>
        </form>

        <div class="text-center mt-3">
            <p>Didn't receive the code? <a href="signup.php" class="btn btn-link">Resend</a></p>
        </div>
    </div>

</body>
</html>
