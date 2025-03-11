<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Search App</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css?family=Droid+Sans:400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.8.1/baguetteBox.min.css">

    <style>
        /* Background Gradient */
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
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

        .gallery-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0px 20px 30px rgba(0, 0, 0, 0.3);
            width: 90% !important;
            height: 80%;
            max-width: none;
            text-align: center;
            transform: translateY(30px);
            transition: all 0.3s ease-in-out;
            overflow-y: auto;
        }

        h1 {
            font-weight: bold;
            color: #333;
        }

        .small-input {
            text-align: center;
            border: 1px solid #ccc;
        }
        /* Image Gallery */
        .tz-gallery {
            padding: 20px;
        }

        .image-card {
            width: 100%;
            max-width: 300px;
        }

        .image-card img {
            width: 100%;
            height: auto;
            display: block;
            border: 10px solid transparent;
            transition: transform 0.3s ease-in-out;
        }

        .image-card .img-caption {
            font-size: 14px;
            display: none;
            color: #333;
            position: relative;
            font-weight: bold;
            border: 10px solid transparent;
            background: rgba(255, 255, 255, 0.8);
            margin: 10px;
            margin-top: -50px;
            z-index: 10;
        }

        .image-card:hover .img-caption {
            display: block;
        }

        .image-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            padding: 10px;
        }

        .lazy-load {
    opacity: 0;
    transition: opacity 1s ease-in-out;
}
        @media (min-width: 768px) {
            .image-card {
                width: calc(50% - 10px);
            }
        }

        @media (min-width: 1024px) {
            .image-card {
                width: calc(33.33% - 10px);
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
                        <a class="btn btn-primary" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-danger" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Floating Search Section -->
    <div class="container gallery-container">
        <h1>Image Gallery</h1>
        <p class="text-center">Search images with hashtags.</p>

        <div class="input-group my-4">
    <input type="text" id="searchQuery" class="form-control" placeholder="Enter hashtag (e.g., nature) and select number of images">
    <input type="number" name="num" id="num" class="small-input" value="3" min="1" max="10">
    <button class="btn btn-primary" id="searchBtn">Search</button>
</div>


        <div class="tz-gallery">
            <div class="row" id="resultsContainer">
                <!-- Images will be dynamically inserted here -->
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.8.1/baguetteBox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/4.2.2/masonry.pkgd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.1.4/imagesloaded.pkgd.min.js"></script>

    <script>
$(document).ready(function () {
    $("#searchBtn").click(function () {
        var hashtag = $("#searchQuery").val().trim();
        var num = $("#num").val();
        if (hashtag === "") {
            alert("Please enter a hashtag.");
            return;
        }

        $("#resultsContainer").html("<p class='text-center'>Loading...</p>");

        $.ajax({
            url: "search.php",
            type: "GET",
            data: { hashtag: hashtag , num_images: num},
            success: function (response) {
                $("#resultsContainer").html(response);

                // Lazy load images with fade-in effect
                $(".lazy-load").each(function () {
                    $(this).on("load", function () {
                        $(this).css("opacity", 1);
                    });
                });

                // Ensure Masonry initializes after images load
                var grid = document.querySelector('#resultsContainer');
                imagesLoaded(grid, function () {
                    new Masonry(grid, {
                        itemSelector: '.image-card',
                        columnWidth: '.image-card',
                        percentPosition: true
                    });
                });

                baguetteBox.run('.tz-gallery'); // Enable lightbox
            },
            error: function () {
                $("#resultsContainer").html("<p class='text-danger text-center'>Error fetching images.</p>");
            }
        });
    });
});

    </script>

</body>

</html>
