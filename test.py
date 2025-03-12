from apify_client import ApifyClient

# Initialize the ApifyClient with your API token
client = ApifyClient("apify_api_B4YUHSyHyqltIYvSaveik1gqftPCe41tl1qO")

# Prepare the Actor input
run_input = {
    "hashtags": ["nature"],
    "resultsType": "posts",
    "resultsLimit": 5,
}

# Run the Actor and wait for it to finish
run = client.actor("reGe1ST3OBgYZSsZJ").call(run_input=run_input)

# Fetch and print Actor results from the run's dataset (if there are any)
for item in client.dataset(run["defaultDatasetId"]).iterate_items():
    print(item.get("displayUrl", ""))