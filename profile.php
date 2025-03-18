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
    $current_password = htmlspecialchars(trim($_POST['current_password']));
    $new_password = htmlspecialchars(trim($_POST['new_password']));
    $confirm_new_password = htmlspecialchars(trim($_POST['confirm_new_password']));

    // Validate inputs
    if (empty($current_password) || empty($new_password) || empty($confirm_new_password)) {
        $error = "All fields are required!";
    } elseif ($new_password !== $confirm_new_password) {
        $error = "New passwords do not match!";
    } else {
        // Fetch stored password hash
        $stmt = $conn->prepare("SELECT password FROM user_details WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($stored_password);
        $stmt->fetch();
        $stmt->close();

        // Verify current password
        if (password_verify($current_password, $stored_password)) {
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
            $error = "Current password is incorrect!";
        }
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
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/form_&_bg.css">

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
                                <small><?= htmlspecialchars($row['search_date']); ?></small>
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