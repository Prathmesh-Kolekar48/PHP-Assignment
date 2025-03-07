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
$num_images = 3; // Adjust the number of images per source

// Flask API URL
$flask_api_url = "http://127.0.0.1:5000/search_images?hashtag=" . urlencode($hashtag) . "&num_images=" . $num_images;

// Fetch data from Flask API
$response = file_get_contents($flask_api_url);
if ($response === FALSE) {
    echo "<p class='text-danger text-center'>Error fetching images.</p>";
    exit();
}

// Decode JSON response
$image_data = json_decode($response, true);

if (!$image_data || empty($image_data)) {
    echo "<p class='text-danger text-center'>No images found.</p>";
    exit();
}

// Display images from all sources
foreach ($image_data as $source => $images) {
    if (!empty($images)) {
        echo "<h3 class='text-center'>" . ucfirst($source) . "</h3><div class='row'>";
        foreach ($images as $img) {
            echo "<div class='col-md-4 image-card'>
                    <img src='$img' class='lazyload' width='100%' height='200px' alt='$hashtag image'>
                  </div>";
        }
        echo "</div>";
    }
}
?>

<script>
// Lazy Loading
document.addEventListener("DOMContentLoaded", function() {
    let lazyImages = document.querySelectorAll(".lazyload");
    lazyImages.forEach(img => {
        img.src = img.dataset.src;
    });
});
</script>
