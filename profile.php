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
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Glassmorphic Navbar */
        .navbar {
            position: absolute;
            top: 20px;
            width: 90%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 10px 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar a {
            color: white !important;
            font-weight: bold;
            transition: 0.3s;
        }

        .navbar a:hover {
            color: #f8f9fa !important;
            transform: scale(1.05);
        }

        .container {
            max-width: 900px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        h2,
        h4 {
            text-align: center;
            font-weight: bold;
            color: #333;
        }

        .btn-primary,
        .btn-secondary {
            width: 100%;
            border-radius: 6px;
            font-size: 16px;
        }

        .profile-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .history-section {
            max-height: 400px;
            overflow-y: auto;
        }

        .history-item {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 8px;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
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

        /* Responsive */
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Image Search</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="btn btn-primary" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-danger" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container">
        <?php echo "<h2>Welcome, " . htmlspecialchars($db_username) . "!</h2>"; ?>
        <?php if ($success)
            echo "<p class='success'>$success</p>"; ?>
        <?php if ($error)
            echo "<p class='error'>$error</p>"; ?>

        <div class="row">
            <!-- Left Section: Profile & Password Change -->
            <div class="col-md-6 profile-section">
                <h4>Profile Details</h4>
                <p><strong>Username:</strong> <?= htmlspecialchars($db_username); ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($email); ?></p>

                <!-- Change Password -->
                <h4 class="mt-4">Change Password</h4>
                <form method="post" action="">
                    <div class="form-group">
                        <input type="password" class="form-control" name="current_password"
                            placeholder="Current Password" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="new_password" placeholder="New Password"
                            required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="confirm_new_password"
                            placeholder="Confirm New Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="change_password">Update Password</button>
                </form>

            </div>

            <!-- Right Section: Search History -->
            <div class="col-md-6 history-section">
                <h4>Search History</h4>
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

            </div>
        </div>
    </div>

</body>

</html>