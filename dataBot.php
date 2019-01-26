<?php
/**
 * name: youtube to mp4 converter
 */
class youtubeConverter
{

    private $videoData = array();
    private $videoDetailUrl = "http://www.youtube.com/get_video_info?video_id=";
    public $videoId = "";

    public function videoId($watchId)
    {
        //gives an error if the video id is empty
        if ($watchId == null) {
            return "Error! Video id is empty";
        }
        $this->videoId = $watchId;

        //get video data
        parse_str($this->getFile($this->videoDetailUrl . $this->videoId), $this->videoData);
    }

    /*
    * Get Video Download Links
    */
    public function videoDownloadLinks()
    {

        // download links and video quality
        $streams = $this->videoData['url_encoded_fmt_stream_map'];
        $streams = explode(',', $streams);
        $linkCount = 0;
        foreach ($streams as $streamData) {
            parse_str($streamData, $streamData);
            $linkArray[$linkCount] = array(); // preparing array

            foreach ($streamData as $key => $value) {
                if ($key == "type") {
                    //if type
                    $value = explode(";", $value)[0];
                    $linkArray[$linkCount][$key] = explode("/", $value)[1];

                } elseif ($key != "itag") {
                    //or itag is not
                    $value = urldecode($value);
                    $linkArray[$linkCount][$key] = $value;

                }
            }
            $linkCount++;
        }

        return $linkArray;
    }

    /*
    *get thumbnail image
    */
    public function getOtherAbout()
    {
        $videoDetails = json_decode($this->videoData['player_response'], true)['videoDetails'];
        return array(
        "channelId" => $videoDetails['channelId'],
        "author" => $this->videoData['author'],
        "image" => $this->videoData['thumbnail_url'],
        "title" => $this->videoData['title'],
        "time" => $this->videoData['length_seconds'],
        "keywords" => $videoDetails['keywords'],
        "description" => $videoDetails['shortDescription'],
        );
    }

    /*
    * Curl ile veri çeker
    */
    private static function getFile($url)
    {
        //Options
        $options = array(CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
        CURLOPT_ENCODING => "",
        CURLOPT_AUTOREFERER => true,
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        );

        //Visit
        $ch = curl_init("$url");
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);

        //Debug
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        //return
        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;
        $header['content'] = $content;
        return str_replace(array("\n", "\r", "\t"), null, $header['content']);
    }
}
