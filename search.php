<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo "<p class='text-danger text-center'>Unauthorized access.</p>";
    exit();
}

if (!isset($_GET['hashtag'])) {
    echo "<p class='text-danger text-center'>Invalid request.</p>";
    exit();
}

$hashtag = htmlspecialchars($_GET['hashtag']);
$num_images = 3; // Number of images to fetch

// Flask API URL
$flask_api_url = "http://127.0.0.1:5000/search_images?hashtag=" . urlencode($hashtag) . "&num_images=" . $num_images;

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

foreach ($image_data as $source => $images) {
    if (!empty($images)) {
        echo "</h3><div class='image-grid'>";
        foreach ($images as $img) {
            echo "<div class='image-card'>
                    <a href='$img' class='lightbox' data-caption='$source'>
                        <img src='$img' alt='$hashtag image'>
                    </a>
                    <div class='img-caption'>$source</div>
            </div>";
        }
        echo "</div>";
    }
}
?>
