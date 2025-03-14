<?php
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_code = $_POST['code'];

    if ($entered_code == $_SESSION['reset_code']) {
        header("Location: reset_password.php");
        exit();
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
    <title>Verify Code | Image Gallery</title>

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

        .verify-container {
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
    </style>
</head>
<body>

    <div class="verify-container">
        <h2>Verify Code</h2>
        <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>

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
