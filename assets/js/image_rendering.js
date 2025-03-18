
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
            data: { hashtag: hashtag, num_images: num },
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
