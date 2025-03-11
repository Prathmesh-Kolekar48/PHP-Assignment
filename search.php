<?php
include 'db_connect.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "<p class='text-danger text-center'>Unauthorized access.</p>";
    exit();
}

// Check if a hashtag is provided
if (!isset($_GET['hashtag'])) {
    echo "<p class='text-danger text-center'>Invalid request.</p>";
    exit();
}

$hashtag = htmlspecialchars($_GET['hashtag']);
$num_images = intval($_GET['num_images']);

// Save search history
$username = $_SESSION['username']; // Get logged-in user's username
$search_date = date("Y-m-d H:i:s"); // Current timestamp

$sql = "INSERT INTO history (username, hashtag, search_date) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $hashtag, $search_date);

if (!$stmt->execute()) {
    echo "<p class='text-danger text-center'>Failed to save search history.</p>";
}

$stmt->close();
$conn->close();

// Flask API URL
$flask_api_url = "https://image-search-backend.vercel.app/search_images?hashtag=" . urlencode($hashtag) . "&num_images=" . $num_images;

// Fetch images from Flask API
$response = file_get_contents($flask_api_url);
if ($response === FALSE) {
    echo "<p class='text-danger text-center'>Error fetching images.</p>";
    exit();
}

$image_data = json_decode($response, true);

if (!$image_data || empty($image_data)) {
    echo "<p class='text-danger text-center'>No images found.</p>";
    exit();
}

// Display images
foreach ($image_data as $source => $images) {
    if (!empty($images)) {
        echo "<div class='image-grid'>";
        foreach ($images as $img) {
            echo "<div class='image-card'>
                    <a href='$img' class='lightbox' data-caption='$source'>
                        <img src='$img' alt='$hashtag image' class='lazy-load' loading='lazy'>
                    </a>
                    <div class='img-caption'>$source</div>
                  </div>";
        }
        echo "</div>";
    }
}
?>
