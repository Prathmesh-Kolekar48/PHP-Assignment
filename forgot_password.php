<?php

require 'handle_forgot_pwd.php';
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
    <link rel="stylesheet" href="assets/css/form_&_bg.css">
</head>
<body>

    <div class="container">
        <h2>Forgot Password</h2>

        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>{$_SESSION['error']}</p>";
            unset($_SESSION['error']);  // Clear error message after displaying
        }
        if (isset($_SESSION['success'])) {
            echo "<p class='success'>{$_SESSION['success']}</p>";
            unset($_SESSION['success']); // Clear success message after displaying
        }
        ?>

        <form action="handle_forgot_pwd.php" method="POST">
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
