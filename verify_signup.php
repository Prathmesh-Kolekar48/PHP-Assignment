<?php
session_start();
include 'db_connect.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_code = $_POST['code'];
    $email = $_SESSION['email'];

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

    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 350px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            font-weight: bold;
            color: #333;
        }

        .form-control {
            border-radius: 6px;
            padding: 12px;
            text-align: center;
            font-size: 18px;
            letter-spacing: 3px;
            font-weight: bold;
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

        .error, .success {
            font-size: 14px;
            margin-top: 10px;
        }

        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

    <div class="container-box">
        <h2>Verify Your Email</h2>
        <p>We've sent a 6-digit code to your email. Enter it below:</p>

        <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>
        <?php if ($success) { echo "<p class='success'>$success</p>"; } ?>

        <form method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="code" required maxlength="6" pattern="\d{6}">
            </div>
            <button type="submit" class="btn btn-primary">Verify</button>
        </form>

        <div class="text-center mt-3">
            <p>Didn't receive the code? <a href="signup.php" class="btn btn-link">Resend</a></p>
        </div>
    </div>

</body>
</html>
