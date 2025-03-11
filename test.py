from googlesearch import search

res = search("#nature images from instagram",stop=5)  # Search for 5 images with hashtag #nature from Instagram

for r in res:
    print(r)  # Print the URLs of the images