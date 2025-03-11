<?php
include 'db_connect.php'; // Include database connection
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$success = $error = "";

// Fetch user details
$stmt = $conn->prepare("SELECT username, email FROM user_details WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($db_username, $email);
$stmt->fetch();
$stmt->close();

// Fetch search history
$history_stmt = $conn->prepare("SELECT hashtag, search_date FROM history WHERE username = ? ORDER BY search_date DESC");
$history_stmt->bind_param("s", $username);
$history_stmt->execute();
$history_result = $history_stmt->get_result();

// Handle password change
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // Fetch stored password hash
    $stmt = $conn->prepare("SELECT password FROM user_details WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();

    // Verify current password
    if (password_verify($current_password, $stored_password)) {
        if ($new_password === $confirm_new_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password in database
            $update_stmt = $conn->prepare("UPDATE user_details SET password = ? WHERE username = ?");
            $update_stmt->bind_param("ss", $hashed_password, $username);
            if ($update_stmt->execute()) {
                $success = "Password changed successfully!";
            } else {
                $error = "Error updating password!";
            }
            $update_stmt->close();
        } else {
            $error = "New passwords do not match!";
        }
    } else {
        $error = "Current password is incorrect!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Image Gallery</title>

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

        .profile-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px; /* Increased size */
        }

        h2, h4 {
            text-align: center;
            font-weight: bold;
            color: #333;
        }

        .btn-primary, .btn-secondary {
            width: 100%;
            border-radius: 6px;
            font-size: 16px;
        }

        .history-list {
            margin-top: 20px;
        }

        .history-item {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 5px;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
        }
        
        .logout-btn:hover {
            background-color: #c82333;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .success {
            color: green;
            font-size: 14px;
            text-align: center;
        }

        .error {
            color: red;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="profile-container">
        <h2>My Profile</h2>
        <?php if ($success) echo "<p class='success'>$success</p>"; ?>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>

        <p><strong>Username:</strong> <?= htmlspecialchars($db_username); ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($email); ?></p>
        
        <h4 class="mt-4">Hashtag Search History</h4>
        <div class="history-list">
            <?php if ($history_result->num_rows > 0): ?>
                <?php while ($row = $history_result->fetch_assoc()): ?>
                    <div class="history-item">
                        <span><?= htmlspecialchars($row['hashtag']); ?></span>
                        <small><?= $row['search_date']; ?></small>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No search history found.</p>
            <?php endif; ?>
        </div>

        <!-- Change Password Section -->
        <h4 class="mt-4">Change Password</h4>
        <form method="post" action="">
            <div class="form-group">
                <input type="password" class="form-control" name="current_password" placeholder="Current Password" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="new_password" placeholder="New Password" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="confirm_new_password" placeholder="Confirm New Password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="change_password">Update Password</button>
        </form>

        <a href="index.php" class="btn btn-primary mt-3">Go to Dashboard</a>
        <a href="logout.php" class="btn logout-btn mt-2">Logout</a>
    </div>

</body>
</html>
