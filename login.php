<?php
session_start();
include 'db_connect.php'; // Include database connection

if (isset($_POST['login'])) {
    // Retrieve and sanitize form data
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Prepare a statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT username, password FROM user_details WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Bind result variables
        $stmt->bind_result($db_username, $hashed_password);
        $stmt->fetch();

        // Verify password against the stored hash
        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $db_username;
            header("Location: index.php");
            exit();
        } else {
            $error = "❌ Invalid username or password.";
        }
    } else {
        $error = "❌ Invalid username or password.";
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Image Gallery</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="assets/css/form_&_bg.css">
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>
        <?php if(isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        
        <form method="post" action="">
            <div class="mb-3">
                <input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="login">Login</button>
            <a href="forgot_password.php" class="btn btn-link">Forgot Password</a></p>
        </form>

        <div class="text-center mt-3">
            <p>Don't have an account? <a href="signup.php" class="btn btn-link">Sign Up</a></p>
        </div>

    </div>

</body>
</html>
