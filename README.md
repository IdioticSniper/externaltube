# ExternalTube
A way to scrape metadata and browse YouTube videos that works off web.archive.org

# Requirements
* ffmpeg, ffprobe
* PHP (w/ PDO installed)
* MySQL server
* Optionally, cURL.
* An unhealthy need for old examples of copyright infringement

# Setup
1. Import database
2. Modify /incl/main/config.php to fit your database credentials and ffmpeg paths
3. Create the folders "/content/video/" and "/content/thumbs/" in the same directory as the PHP files

# How to scrape
Send a GET request to any of the files in /parsers/ with these parameters: 
* ?cdn=(YouTube CDN subdomain, for example "v45") (optional)
* ?scrape=(Video ID) (required)

For example, sending a cURL request to "http://localhost:81/parsers/2007e.php?scrape=jNQXAC9IVRw" will scrape metadata for "Me at the zoo" and download it's video file.
What the public instance of the website does is work off of manually created URL lists (due to how rough the concept is at the moment), but I'm looking to make a more automated solution.

# To-do list
* Related videos
* Better search
* Automate making URL lists out of YouTube CDN's
* Better DOM parsing

# Notes
- The only included DOM parser currently only supports late 2006-late 2007 YouTube watch pages.
- Search is tag and uploader-name only.
