import requests

def fetch_instagram_images(hashtag):
    api_key = ""  # Replace with your API Key
    cx = "b6207a641a6f84aa7"  # Replace with your Custom Search Engine ID
    search_query = f"{hashtag}"  # Search Instagram for the hashtag
    search_url = f"https://www.googleapis.com/customsearch/v1?q={search_query}&cx={cx}&searchType=image&key={api_key}"

    response = requests.get(search_url)
    results = response.json()

    if "items" in results:
        for item in results["items"]:
            print(item["link"])  # Prints image URL
    else:
        print(f"No images found for #{hashtag}")

# Example usage
fetch_instagram_images("nature")
