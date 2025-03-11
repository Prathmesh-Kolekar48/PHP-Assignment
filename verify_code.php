<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_code = $_POST['code'];
    
    if ($entered_code == $_SESSION['reset_code']) {
        echo "<p style='color:green;'>Code verified! You can reset your password.</p>";
        header("Location: reset_password.php");
        exit();
    } else {
        echo "<p style='color:red;'>Invalid code. Try again!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify Code</title>
</head>
<body>
    <h2>Enter Verification Code</h2>
    <form action="verify_code.php" method="POST">
        <input type="text" name="code" placeholder="Enter the code" required>
        <button type="submit">Verify</button>
    </form>
</body>
</html>
