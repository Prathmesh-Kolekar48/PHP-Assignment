<?php
session_start();

if (isset($_POST['login'])) {
    // Database credentials
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '12345678';
    $db_name = 'user_data';

    // Create connection
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve and sanitize form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];

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
            // Store user session using only the username
            $_SESSION['username'] = $db_username;

            // Redirect to dashboard
            header("Location: index.php");
            exit();
        } else {
            $error = "❌ Invalid username or password.";
        }
    } else {
        $error = "❌ Invalid username or password.";
    }
    
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f2f2f2; }
        .login-container {
            width: 300px;
            padding: 20px;
            background-color: #fff;
            margin: 100px auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; }
        input[type=text], input[type=password] {
            width: 100%;
            padding: 10px;
            margin: 8px 0 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type=submit] {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            border: none;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type=submit]:hover {
            background-color: #4cae4c;
        }
        .error { color: red; margin-bottom: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>User Login</h2>
        <?php if(isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="post" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="login" value="Login">
        </form>
    </div>
</body>
</html>
