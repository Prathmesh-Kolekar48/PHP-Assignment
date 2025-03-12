<?php
session_start();
require 'db_connect.php'; // Database connection

$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // Validate password
    if (strlen($password) < 8) {
        $error = "❌ Password must be at least 8 characters long!";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error = "❌ Password must contain at least one uppercase letter!";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $error = "❌ Password must contain at least one lowercase letter!";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = "❌ Password must contain at least one number!";
    } elseif (!preg_match('/[\W]/', $password)) {
        $error = "❌ Password must contain at least one special character!";
    } elseif ($password !== $confirm_password) {
        $error = "❌ Passwords do not match!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $email = $_SESSION['reset_email'];

        $stmt = $conn->prepare("UPDATE user_details SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            $success = "✅ Password updated successfully! <a href='login.php'>Login</a>";
            session_destroy();
        } else {
            $error = "❌ Error updating password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Image Gallery</title>

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

        .reset-container {
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

        .error, .success {
            font-size: 14px;
            text-align: center;
            margin-bottom: 15px;
        }

        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

    <div class="reset-container">
        <h2>Reset Password</h2>

        <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>
        <?php if ($success) { echo "<p class='success'>$success</p>"; } ?>

        <form method="post">
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Enter new password" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm new password" required>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>

</body>
</html>
