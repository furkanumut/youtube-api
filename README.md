# youtube-api
1. Video download links
  - url
  - quality
  - type
2. Video About
  - channelId
  - author
  - image
  - title
  - time
  - keywords
  - description
  
---
```php
<?php 

include_once 'dataBot.php';
$youtubeConverter = new youtubeConverter;

# enter the youtube video id
# video id jNQXAC9IVRw for https://www.youtube.com/watch?v=jNQXAC9IVRw
$youtubeConverter->videoId("$videoId"); 

$videoLinks = $youtubeConverter->videoDownloadLinks();
$videoAbout = $youtubeConverter->getOtherAbout();

echo "<h1> Download Links </h1>";
print_r($videoLinks);
echo "<br>";
echo "<h1>Video About</h1>";
print_r($videoAbout);
