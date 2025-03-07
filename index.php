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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .search-results {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .search-results img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 5px;
        }
        .image-card {
            margin: 10px;
            padding: 10px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .image-card:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Image Search</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="history.php">Search History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Search Section -->
<div class="container">
    <h2 class="text-center">Search Images by Hashtag</h2>
    <div class="input-group my-4">
        <input type="text" id="searchQuery" class="form-control" placeholder="Enter hashtag (e.g., nature)">
        <button class="btn btn-primary" id="searchBtn">Search</button>
    </div>

    <!-- Search Results -->
    <div class="row search-results" id="resultsContainer">
        <!-- Images will be dynamically inserted here -->
    </div>
</div>

<script>
$(document).ready(function() {
    $("#searchBtn").click(function() {
        var hashtag = $("#searchQuery").val().trim();
        if (hashtag === "") {
            alert("Please enter a hashtag.");
            return;
        }

        $("#resultsContainer").html("<p class='text-center'>Loading...</p>");

        $.ajax({
            url: "search.php",
            type: "GET",
            data: { hashtag: hashtag },
            success: function(response) {
                $("#resultsContainer").html(response);
            },
            error: function() {
                $("#resultsContainer").html("<p class='text-danger text-center'>Error fetching images.</p>");
            }
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
