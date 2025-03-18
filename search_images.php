<?php

header("Content-Type: application/json");

// Utility function to fetch images from DuckDuckGo
function getImageUrls($source, $hashtag, $num_images = 5) {
    $search_query = "site:$source $hashtag images";
    $search_url = "https://duckduckgo.com/?q=" . urlencode($search_query) . "&t=h_&iax=images&ia=images";

    $context = stream_context_create([
        "http" => ["header" => "User-Agent: Mozilla/5.0"]
    ]);

    $html = file_get_contents($search_url, false, $context);

    preg_match_all('/"image":"(.*?)"/', $html, $matches);
    
    return array_slice(array_unique($matches[1]), 0, $num_images);
}

// Utility function to fetch images from Jina AI
function getImagesFromJina($target_url, $num_images = 5) {
    $jina_url = 'https://r.jina.ai/';
    $headers = [
        "Accept: application/json",
        "Authorization: Bearer jina_eff2fa7fb131424ca1bfc6b8ff7d2b3a1FCFzrBGkyuY3Biw3F9B2k1GWQOt",
        "Content-Type: application/json",
        "X-Engine: direct",
        "X-With-Images-Summary: true"
    ];
    
    $data = json_encode(["url" => $target_url]);

    $ch = curl_init($jina_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
    $response = curl_exec($ch);
    curl_close($ch);

    $json_data = json_decode($response, true);

    $images = [];
    if (isset($json_data['data']['images'])) {
        foreach ($json_data['data']['images'] as $item) {
            if (strpos($item, 'iso.500px.com') !== false && strpos($item, 'logo') === false) {
                $images[] = $item;
            }
            if (count($images) >= $num_images) {
                break;
            }
        }
    }

    return $images;
}

// Utility function to fetch images from Flickr
function getFlickrImages($hashtag, $num_images = 5) {
    $FLICKR_API_KEY = "1eac120caf0d5106e1368f3682436eab";
    $url = "https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=$FLICKR_API_KEY&tags=" . urlencode($hashtag) . "&per_page=$num_images&format=json&nojsoncallback=1";

    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    $images = [];
    if (isset($data["photos"]["photo"])) {
        foreach ($data["photos"]["photo"] as $photo) {
            $images[] = "https://farm{$photo['farm']}.staticflickr.com/{$photo['server']}/{$photo['id']}_{$photo['secret']}_b.jpg";
        }
    }

    return $images;
}

// Utility function to fetch images from Instagram using Apify
function getInstagramImages($hashtag, $num_images = 5) {
    $APIFY_TOKEN = "apify_api_B4YUHSyHyqltIYvSaveik1gqftPCe41tl1qO";
    $api_url = "https://api.apify.com/v2/acts/reGe1ST3OBgYZSsZJ/runs?token=$APIFY_TOKEN";

    $post_data = json_encode([
        "hashtags" => [$hashtag],
        "resultsType" => "posts",
        "resultsLimit" => $num_images
    ]);

    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    $images = [];
    if (isset($data["data"])) {
        foreach ($data["data"] as $post) {
            if (!empty($post["displayUrl"])) {
                $images[] = $post["displayUrl"];
            }
        }
    }

    return $images;
}

// Get parameters from the request
$hashtag = isset($_GET['hashtag']) ? trim($_GET['hashtag']) : "";
$num_images = isset($_GET['num_images']) ? intval($_GET['num_images']) : 5;

if (empty($hashtag)) {
    echo json_encode(["error" => "Hashtag parameter is required"]);
    exit();
}

// Sources for DuckDuckGo search
$sources = [
    "pinterest" => "pinterest.com"
];

// Fetch images from different sources
$image_data = [];

foreach ($sources as $source_name => $domain) {
    $image_data[$source_name] = getImageUrls($domain, $hashtag, $num_images);
}

// Fetch images from 500px
$image_data["500px"] = getImagesFromJina("https://iso.500px.com/tag/$hashtag/", $num_images);

// Fetch images from Flickr API
$image_data["flickr"] = getFlickrImages($hashtag, $num_images);

// Fetch images from Instagram API
$image_data["instagram"] = getInstagramImages($hashtag, $num_images);

// Return JSON response
echo json_encode($image_data, JSON_PRETTY_PRINT);

?>
