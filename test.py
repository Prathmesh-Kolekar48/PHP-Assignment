from duckduckgo_search import DDGS

def get_pinterest_image_urls(hashtag, num_images=10):
    with DDGS() as ddgs:
        results = list(ddgs.images(f"site:flickr.com #{hashtag}", max_results=num_images))
    
    image_urls = [img["image"] for img in results]
    return image_urls

# Example usage
hashtag = "selfie"
image_urls = get_pinterest_image_urls(hashtag, num_images=5)

# Print URLs
for url in image_urls:
    print(url)
