<?php
session_start();

// Check if form is submitted
if (isset($_POST['signup'])) {
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

    // Get form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = $_POST['email'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if username already exists
        $stmt = $conn->prepare("SELECT username, password FROM user_details WHERE username = ?");
        $stmt->bind_param("s", $username);  
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username already exists!";
        } else {
            // Insert new user into database
            $stmt = $conn->prepare("INSERT INTO user_details (username, email, password, verified) VALUES (?, ?, ?, 0)");
            $stmt->bind_param("sss", $username, $email, $hashed_password); 

            if ($stmt->execute()) {
                $success = "Account created successfully! <a href='login.php'>Login here</a>";
            } else {
                $error = "Error creating account!";
            }
        }

        $stmt->close();
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Signup</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f2f2f2; }
        .signup-container {
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
        .error, .success { text-align: center; margin-bottom: 10px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Signup</h2>
        <?php if(isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <?php if(isset($success)) { echo "<p class='success'>$success</p>"; } ?>
        <form method="post" action="">
        <input type="text" name="email" placeholder="Email" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <input type="submit" name="signup" value="Signup">
        </form>
    </div>
</body>
</html>
