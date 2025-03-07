from flask import Flask, request, jsonify
from duckduckgo_search import DDGS
import os
import requests
from flask_cors import CORS

app = Flask(__name__)
CORS(app)  # Enables Cross-Origin Resource Sharing (for frontend support)

def get_image_urls(source, hashtag, num_images=5):
    """
    Fetch images from a given source using DuckDuckGo search.
    """
    search_query = f"site:{source} {hashtag} images"
    results = []

    try:
        with DDGS() as ddgs:
            results = list(ddgs.images(search_query, max_results=num_images))
    except Exception as e:
        print(f"Error fetching images from {source}: {e}")
    
    return [img["image"] for img in results] if results else []

def get_images_from_jina(target_url, num_images=5):
    """
    Generic method to fetch images from a given webpage URL using the Jina AI API.
    """
    jina_url = 'https://r.jina.ai/'
    headers = {
        "Accept": "application/json",
        "Authorization": "Bearer jina_f63ea977fd2541d1b2b0d8cd6fb45b07-bPMfqoo9LcDrubaauf654m77sg5",
        "Content-Type": "application/json",
        "X-Locale": "en-US",
        "X-With-Images-Summary": "true"
    }
    data = {"url": target_url}
    try:
        response = requests.post(jina_url, headers=headers, json=data, timeout=10)
        response.raise_for_status()
        json_data = response.json()
        # print(json_data)
        images = []
        if "images" in json_data['data']:
            print(json_data['data']["images"])
            for item in json_data['data']["images"].values():
                if 'iso.500px.com' in item and 'logo' not in item:
                    images.append(item)
                if len(images) >= num_images:
                    break

        return images
    except Exception as e:
        print(f"Error fetching images from {target_url}: {e}")
        return []

def get_flickr_images(hashtag, num_images=5):
    """
    Fetch images from Flickr API.
    """
    FLICKR_API_KEY = "1eac120caf0d5106e1368f3682436eab"
    url = "https://api.flickr.com/services/rest/"
    params = {
        "method": "flickr.photos.search",
        "api_key": FLICKR_API_KEY,
        "tags": hashtag,
        "per_page": num_images,
        "format": "json",
        "nojsoncallback": 1
    }

    try:
        response = requests.get(url, params=params, timeout=10)
        response.raise_for_status()
        data = response.json()
        # print(data)
        if "photos" in data:
            photos = data["photos"]["photo"]
            # print(photos)
            res = [f"https://farm{photo['farm']}.staticflickr.com/{photo['server']}/{photo['id']}_{photo['secret']}_b.jpg" for photo in photos]
            # print(res)
            return res
        
    except Exception as e:
        print(f"Error fetching Flickr images: {e}")
    
    return []

@app.route('/search_images', methods=['GET'])
def search_images():
    """
    API endpoint to search images from different sources.
    """
    hashtag = request.args.get('hashtag', '').strip()
    try:
        num_images = int(request.args.get('num_images', 5))
    except ValueError:
        return jsonify({"error": "num_images must be an integer"}), 400

    if not hashtag:
        return jsonify({"error": "Hashtag parameter is required"}), 400

    # Sources for DuckDuckGo-based search
    sources = {
        "pinterest": "pinterest.com",
        # "instagram": "instagram.com"  # Uncomment if needed
    }

    image_data = {}

    # Fetch from DuckDuckGo search
    for source_name, domain in sources.items():
        image_data[source_name] = get_image_urls(domain, hashtag, num_images)

    # Fetch from 500px
    image_data["500px"] = get_images_from_jina(f"https://iso.500px.com/tag/{hashtag}/", num_images)

    # Fetch from Flickr API
    image_data["flickr"] = get_flickr_images(hashtag, num_images)

    return jsonify(image_data)

if __name__ == '__main__':
    app.run(debug=True)
