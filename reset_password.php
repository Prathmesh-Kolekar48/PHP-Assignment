<?php
session_start();
require 'db_connect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
    $email = $_SESSION['reset_email'];

    $stmt = $conn->prepare("UPDATE user_details SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $new_password, $email);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Password updated successfully! <a href='login.php'>Login</a></p>";
        session_destroy();
    } else {
        echo "<p style='color:red;'>Error updating password!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form method="post">
        <input type="password" name="password" required placeholder="Enter new password">
        <button type="submit">Reset</button>
    </form>
</body>
</html>
